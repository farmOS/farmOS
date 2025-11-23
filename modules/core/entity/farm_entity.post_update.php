<?php

/**
 * @file
 * Post update hooks for the farm_entity module.
 */

declare(strict_types=1);

use Drupal\Core\Database\Database;

/**
 * Enforce entity reference integrity on plan reference fields.
 */
function farm_entity_post_update_enforce_plan_eri(&$sandbox) {
  $config = \Drupal::configFactory()->getEditable('entity_reference_integrity_enforce.settings');
  $entity_types = $config->get('enabled_entity_type_ids');
  $entity_types['plan'] = 'plan';
  $config->set('enabled_entity_type_ids', $entity_types);
  $config->save();
}

/**
 * Rebuild bundle field maps.
 */
function farm_entity_post_update_rebuild_bundle_field_maps(&$sandbox = NULL) {
  \Drupal::service('entity_field.manager')->rebuildBundleFieldMap();
}

/**
 * Uninstall EXIF Orientation module.
 */
function farm_entity_post_update_uninstall_exif_orientation() {
  if (\Drupal::service('module_handler')->moduleExists('exif_orientation')) {
    $modules = \Drupal::service('extension.list.module')->reset()->getList();
    if (empty($modules['exif_orientation']->required_by)) {
      \Drupal::service('module_installer')->uninstall(['exif_orientation']);
    }
  }
}

/**
 * Update log revisions with revision_user set to anonymous user.
 */
function farm_entity_post_update_update_anonymous_log_revisions(&$sandbox = NULL) {

  // Create a subquery to get the original creator uid.
  $connection = Database::getConnection();
  $subquery = $connection->select('log_field_revision', 'lfr')
    ->fields('lfr', ['uid'])
    ->where('lfr.id = log_revision.id')
    ->where('lfr.revision_id = log_revision.revision_id');

  // Update anonymous log revision_user to the original creator uid.
  $updated = $connection->update('log_revision')
    ->expression('revision_user', "($subquery)")
    ->condition('revision_user', 0)
    ->execute();

  \Drupal::logger('farm_entity')->notice('Updated @count log revisions with correct revision_user.', ['@count' => $updated]);
}
