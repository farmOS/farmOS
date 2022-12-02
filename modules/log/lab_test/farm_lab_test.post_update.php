<?php

/**
 * @file
 * Post update hooks for the farm_lab_test module.
 */

use Drupal\Core\Utility\UpdateException;
use Drupal\farm_lab_test\Entity\FarmLabTestType;
use Drupal\taxonomy\Entity\Term;

/**
 * Add "Date received" and "Date processed" fields to lab test logs.
 */
function farm_lab_test_post_update_add_received_processed_date_fields(&$sandbox) {

  // Date received.
  $options = [
    'type' => 'timestamp',
    'label' => t('Date received'),
    'description' => t('The date when the sample was received by the lab.'),
    'weight' => [
      'form' => -35,
      'view' => -35,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('lab_received_date', 'log', 'farm_lab_test', $field_definition);

  // Date processed.
  $options = [
    'type' => 'timestamp',
    'label' => t('Date processed'),
    'description' => t('The date when the sample was processed by the lab.'),
    'weight' => [
      'form' => -34,
      'view' => -34,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('lab_processed_date', 'log', 'farm_lab_test', $field_definition);
}

/**
 * Install the lab taxonomy module.
 */
function farm_lab_test_post_update_enable_farm_lab(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_lab')) {
    \Drupal::service('module_installer')->install(['farm_lab']);
  }
}

/**
 * Migrate laboratory names to taxonomy terms.
 */
function farm_lab_test_post_update_migrate_lab_terms(&$sandbox) {

  // This function will be run as a batch operation to save the new term
  // reference values on existing logs. On the first run, we will make
  // preparations. This logic should only run once.
  if (!isset($sandbox['current_log'])) {

    // Query the database for all lab field data on logs.
    // Save it to $sandbox for future reference.
    $sandbox['log_map'] = \Drupal::database()->query('SELECT entity_id, lab_value FROM {log__lab} WHERE deleted = 0')->fetchCol();

    // Create taxonomy terms for each of the labs.
    // Add them to a term map in $sandbox for future reference.
    $unique_labs = array_unique($sandbox['log_map']);
    $sandbox['term_map'] = [];
    foreach ($unique_labs as $lab_name) {
      $term = Term::create(['vid' => 'lab', 'name' => $lab_name]);
      $term->save();
      $sandbox['term_map'][$lab_name] = $term->id();
    }

    // Get the Drupal entity definition update manager.
    $update_manager = \Drupal::entityDefinitionUpdateManager();

    // Delete the old lab field.
    $storage_definition = $update_manager->getFieldStorageDefinition('lab', 'log');
    $update_manager->uninstallFieldStorageDefinition($storage_definition);

    // Install the new lab field.
    $options = [
      'type' => 'entity_reference',
      'label' => t('Laboratory'),
      'description' => t('What laboratory performed this test?'),
      'target_type' => 'taxonomy_term',
      'target_bundle' => 'lab',
      'auto_create' => TRUE,
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
    $update_manager->installFieldStorageDefinition('lab', 'log', 'farm_lab_test', $field_definition);

    // If there are no lab test logs with lab field values, bail.
    if (empty($sandbox['log_map'])) {
      return NULL;
    }

    // Track progress.
    $sandbox['current_log'] = 0;
    $sandbox['#finished'] = 0;
  }

  // Iterate over logs, 10 at a time.
  $log_ids = array_keys($sandbox['log_map']);
  $log_count = count($log_ids);
  $end_log = $sandbox['current_log'] + 10;
  $end_log = $end_log > $log_count ? $log_count : $end_log;
  for ($i = $sandbox['current_log']; $i < $end_log; $i++) {

    // Iterate the global counter.
    $sandbox['current_log']++;

    // Get the log ID.
    $id = $log_ids[$i];

    // If there is no taxonomy term to assign, skip.
    if (empty($sandbox['log_map'][$id]) || empty($sandbox['term_map'][$sandbox['log_map'][$id]])) {
      continue;
    }

    // Load the log.
    $log = \Drupal::service('entity_type.manager')->getStorage('log')->load($id);

    // If the log didn't load, throw an update exception.
    if (empty($log)) {
      throw new UpdateException('Could not load log. ID: @id', ['@id' => $id]);
    }

    // Assign the new lab taxonomy term.
    $log->set('lab', $sandbox['term_map'][$sandbox['log_map'][$id]]);

    // Save a new revision of the log.
    $log->setNewRevision(TRUE);
    $log->setRevisionLogMessage(t('Automatically migrated laboratory name to taxonomy term in the new Lab vocabulary.'));
    $log->save();

    // Declare that the log has been fixed.
    \Drupal::logger('farm_lab_test')->notice(t('Log @id lab has been migrated.', ['@id' => $id]));
  }

  // Update progress.
  $sandbox['#finished'] = $sandbox['current_log'] / count($sandbox['log_map']);
  return NULL;
}

/**
 * Install the test quantity module.
 */
function farm_lab_test_post_update_enable_farm_quantity_test(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_quantity_test')) {
    \Drupal::service('module_installer')->install(['farm_quantity_test']);
  }
}

/**
 * Add tissue lab test type.
 */
function farm_lab_test_post_update_add_tissue_type(&$sandbox) {
  $type = FarmLabTestType::create([
    'id' => 'tissue',
    'label' => 'Tissue test',
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_lab_test',
        ],
      ],
    ],
  ]);
  $type->save();
}
