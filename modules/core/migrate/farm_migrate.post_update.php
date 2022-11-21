<?php

/**
 * @file
 * Post update hooks for the farm_migrate module.
 */

use Drupal\Core\Database\Database;
use Drupal\Core\Site\Settings;
use Drupal\Core\Utility\UpdateException;

/**
 * Fix migrated input log quantity materials.
 */
function farm_migrate_post_update_fix_input_log_quantity_materials(&$sandbox) {

  // For more information see https://github.com/farmOS/farmOS/issues/579.

  // This update can be skipped by adding:
  // $settings['farm_migrate_skip_input_log_migration_fix'] = TRUE;
  // to settings.php.
  if (Settings::get('farm_migrate_skip_input_log_migration_fix', FALSE) === TRUE) {
    return t("Skipping automatic fix of input log material quantities because of this line in settings.php: \$settings['farm_migrate_skip_input_log_migration_fix'] = TRUE;");
  }

  // This function will run as a batch operation, so ensure that the initial
  // setup only runs once.
  if (!isset($sandbox['current_log'])) {

    // If the material quantity module is not enabled, there is nothing to fix.
    if (!\Drupal::moduleHandler()->moduleExists('farm_quantity_material')) {
      return NULL;
    }

    // Check to see if there are logs that suffer from the issue described in
    // https://github.com/farmOS/farmOS/issues/579 (logs with multiple material
    // quantities that all reference the same material term).
    $database = \Drupal::database();
    $query = $database->select('log', 'l');
    $query->addField('l', 'id');
    $query->join('log__quantity', 'lq', 'l.id = lq.entity_id AND l.revision_id = lq.revision_id');
    $query->join('quantity', 'q', 'lq.quantity_target_id = q.id');
    $query->join('quantity__material_type', 'mt', 'q.id = mt.entity_id AND q.revision_id = mt.revision_id AND mt.deleted = 0');
    $query->condition('l.type', 'input');
    $query->condition('lq.deleted', 0);
    $query->condition('q.type', 'material');
    $query->groupBy('l.id');
    $query->groupBy('mt.material_type_target_id');
    $query->having('COUNT(lq.quantity_target_id) > 1');
    $log_ids = $query->execute()->fetchCol();

    // If there are no affected logs, bail.
    if (empty($log_ids)) {
      return NULL;
    }

    // Query the mapping of v1 to v2 input logs, filtering out logs that are
    // not in the affected list. This serves to ensure that the only logs we
    // act upon are those that a) are currently affected, and b) were migrated.
    // We do not want to act upon logs that were not migrated. This also serves
    // to throw an exception if the migrate mapping table is missing from the
    // database.
    $log_map = [];
    $result = $database->query("SELECT sourceid1, destid1 FROM {migrate_map_farm_migrate_log_input} WHERE destid1 IN (:ids[])", [':ids[]' => $log_ids])->fetchAll();
    foreach ($result as $row) {
      if (!empty($row->sourceid1) && !empty($row->destid1)) {
        $log_map[$row->sourceid1] = $row->destid1;
      }
    }

    // If there are no logs in the mapping, bail.
    if (empty($log_map)) {
      return NULL;
    }

    // Take a moment to explain what is happening, and how to skip this update
    // from running.
    \Drupal::logger('farm_migrate')->notice(t("This update will attempt to fix an issue with the migration of input log material data from farmOS v1. To skip this update, add this line to settings.php: \$settings['farm_migrate_skip_input_log_migration_fix'] = TRUE;"));
    \Drupal::logger('farm_migrate')->notice(t('There are @count logs affected by this issue.', ['@count' => count($log_map)]));

    // Check to see if a 1.x database connection is available.
    // This assumes that the site admin followed the recommended steps in the
    // migration docs.
    try {
      $migrate_database = Database::getConnection('default', 'migrate');
      $migrate_database->query("SELECT COUNT(*) FROM {log} WHERE type = 'farm_input'")->fetchField();
    }
    catch (\Exception $e) {
      throw new UpdateException('Could not connect to the farmOS v1 database. Add `migrate` database to settings.php and re-run this update to continue.');
    }

    // Build a map of v1 log IDs to their v1 material term IDs.
    // Order by the original v1 deltas.
    // Create an index of term IDs that were affected.
    $material_tids = [];
    $v1_log_materials = [];
    $result = $migrate_database->query("SELECT entity_id as log_id, field_farm_material_tid as tid FROM {field_data_field_farm_material} WHERE entity_type = 'log' AND bundle = 'farm_input' AND entity_id IN (:ids[]) ORDER BY delta ASC", [':ids[]' => array_keys($log_map)])->fetchAll();
    foreach ($result as $row) {
      if (!empty($row->tid) && !empty($row->log_id)) {
        $material_tids[] = $row->tid;
        $v1_log_materials[$row->log_id][] = $row->tid;
      }
    }

    // Build a map of migrated v1 to v2 term IDs.
    $material_map = [];
    $result = $database->query('SELECT sourceid1, destid1 FROM {migrate_map_farm_migrate_taxonomy_material_type} WHERE sourceid1 IN (:ids[])', [':ids[]' => $material_tids])->fetchAll();
    foreach ($result as $row) {
      if (!empty($row->sourceid1) && !empty($row->destid1)) {
        $material_map[$row->sourceid1] = $row->destid1;
      }
    }

    // Build a map of v2 log IDs to their expected v2 material term IDs.
    $v2_log_materials = [];
    foreach ($v1_log_materials as $v1_log_id => $v1_tids) {
      foreach ($v1_tids as $v1_tid) {
        $v2_log_materials[$log_map[$v1_log_id]][] = $material_map[$v1_tid];
      }
    }

    // Save maps to the sandbox for future iterations.
    $sandbox['log_map'] = $log_map;
    $sandbox['material_map'] = $material_map;
    $sandbox['v1_log_materials'] = $v1_log_materials;
    $sandbox['v2_log_materials'] = $v2_log_materials;

    // Keep track of which logs are skipped.
    // In some situations, we need to be careful about our assumptions, and
    // err on the side of caution. Things may have changed since the migration,
    // so we'll look for a few hints and skip the log if there is any doubt.
    $sandbox['skipped_logs'] = [];

    // Track progress.
    $sandbox['current_log'] = 0;
    $sandbox['#finished'] = 0;
  }

  // Iterate over logs, 10 at a time.
  $log_ids = array_values($sandbox['log_map']);
  $log_count = count($log_ids);
  $end_log = $sandbox['current_log'] + 10;
  $end_log = $end_log > $log_count ? $log_count : $end_log;
  for ($i = $sandbox['current_log']; $i < $end_log; $i++) {

    // Iterate the global counter.
    $sandbox['current_log']++;

    // Load the log.
    $id = $log_ids[$i];
    $log = \Drupal::service('entity_type.manager')->getStorage('log')->load($id);

    // If the log didn't load, throw an update exception.
    if (empty($log)) {
      throw new UpdateException('Could not load log. ID: @id', ['@id' => $id]);
    }

    // Load log quantities and filter out all but material quantities.
    /** @var \Drupal\quantity\Entity\QuantityInterface[] $quantities */
    $quantities = $log->get('quantity')->referencedEntities();
    /** @var \Drupal\quantity\Entity\QuantityInterface[] $material_quantities */
    $material_quantities = array_filter($quantities, function ($quantity) {
      return $quantity->bundle() == 'material';
    });

    // If the number of material quantities does not match the number of
    // material terms that were referenced on the v1 log, skip the log.
    if (count($material_quantities) != count($sandbox['v1_log_materials'][$id])) {
      $sandbox['skipped_logs'][] = $id;
      continue;
    }

    // Iterate over the material quantities and perform some rough checks to be
    // confident that this log has not been touched since migration. If there is
    // any doubt, we skip it.
    foreach ($material_quantities as $quantity) {

      // If the quantity label, value, or units are not empty, skip the log.
      // Material quantities created by the migration ONLY fill in the material
      // term reference. If any other quantity fields are filled it, it would
      // indicate that this quantity has been edited by the user afterwards.
      // If this is the case we can't be sure we know what the term should be.
      if (!($quantity->get('label')->isEmpty() && $quantity->get('value')->isEmpty() && $quantity->get('units')->isEmpty())) {
        $sandbox['skipped_logs'][] = $id;
        continue 2;
      }

      // Load the referenced material term. If empty, skip the log.
      $material_term = $quantity->get('material_type')->referencedEntities()[0];
      if (empty($material_term)) {
        $sandbox['skipped_logs'][] = $id;
        continue 2;
      }

      // If the term is not in the list of expected terms, skip the log.
      if (!in_array($material_term->id(), $sandbox['v2_log_materials'][$id])) {
        $sandbox['skipped_logs'][] = $id;
        continue 2;
      }
    }

    // Now that we are confident that we can proceed, iterate over the material
    // quantities again. This time we will update them. We do this in a
    // separate loop to ensure that we only start updating quantities when we
    // are sure all of them passed the tests above.
    // Keep track of which quantities get updated, and the delta order.
    $quantities_updated = [];
    $delta = 0;
    foreach ($material_quantities as $quantity) {

      // Load the currently referenced material term.
      /** @var \Drupal\taxonomy\TermInterface $current_term */
      $current_term = $quantity->get('material_type')->referencedEntities()[0];

      // Load the expected material term.
      $expected_tid = $sandbox['v2_log_materials'][$id][$delta];
      /** @var \Drupal\taxonomy\TermInterface $expected_term */
      $expected_term = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->load($expected_tid);

      // If the quantity's material term ID does not match the expected value,
      // update it and save a new revision.
      if ($current_term->id() != $expected_term->id()) {
        $quantity->set('material_type', $expected_term);
        $quantity->setNewRevision(TRUE);
        $quantity->setRevisionLogMessage(t('Changed @old_term to @new_term. See: https://github.com/farmOS/farmOS/issues/579', ['@old_term' => $current_term->label(), '@new_term' => $expected_term->label()]));
        $quantity->save();
        $quantities_updated[$quantity->id()] = $quantity;
      }

      // Increment the delta.
      $delta++;
    }

    // If none of the quantities were updated, mark the log as skipped.
    if (empty($quantities_updated)) {
      $sandbox['skipped_logs'][] = $id;
      continue;
    }

    // Update the log's entity reference revision IDs for updated quantities.
    foreach ($log->get('quantity') as $qty_ref) {
      if (array_key_exists($qty_ref->target_id, $quantities_updated)) {
        $qty_ref->setValue($quantities_updated[$qty_ref->target_id]);
      }
    }

    // Save a new revision of the log.
    $log->setNewRevision(TRUE);
    $log->setRevisionLogMessage(t('Automatically fixed quantity material term references. See: https://github.com/farmOS/farmOS/issues/579'));
    $log->save();

    // Declare that the log has been fixed.
    \Drupal::logger('farm_migrate')->notice(t('Log @id has been fixed.', ['@id' => $id]));
  }

  // Update progress.
  $sandbox['#finished'] = $sandbox['current_log'] / count($sandbox['log_map']);

  // Log the list of IDs that were skipped at the end.
  if ($sandbox['#finished'] == 1 && !empty($sandbox['skipped_logs'])) {
    \Drupal::logger('farm_migrate')->warning(t('The following logs were skipped: @log_ids', ['@log_ids' => implode(', ', $sandbox['skipped_logs'])]));
  }
  return NULL;
}
