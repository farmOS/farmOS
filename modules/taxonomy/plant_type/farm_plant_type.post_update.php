<?php

/**
 * @file
 * Post update hooks for the farm_plant_type module.
 */

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Delete default form/view display config for plant type fields.
 */
function farm_plant_type_post_update_delete_display_config(&$sandbox) {
  // We only do this if the farm_update module is enabled, under the assumption
  // that farm_update would be keeping these configurations in a default state.
  if (\Drupal::moduleHandler()->moduleExists('farm_update')) {
    $form_display_config = EntityFormDisplay::load('taxonomy_term.plant_type.default');
    $form_display_config->delete();
    $view_display_config = EntityViewDisplay::load('taxonomy_term.plant_type.default');
    $view_display_config->delete();
  }
}

/**
 * Set the minimum value of maturity_days and transplant_days to 1.
 */
function farm_plant_type_post_update_min_1_day(&$sandbox) {
  // Set the min setting of both fields to 1.
  $field_names = [
    'maturity_days',
    'transplant_days',
  ];
  foreach ($field_names as $field_name) {
    $field = FieldConfig::load('taxonomy_term.plant_type.' . $field_name);
    if (!empty($field)) {
      $field->setSetting('min', 1);
      $field->save();
    }
  }

  // Delete any zero values from the database.
  $tables = [
    'taxonomy_term__maturity_days' => 'maturity_days_value',
    'taxonomy_term__transplant_days' => 'transplant_days_value',
    'taxonomy_term_revision__maturity_days' => 'maturity_days_value',
    'taxonomy_term_revision__transplant_days' => 'transplant_days_value',
  ];
  foreach ($tables as $table => $column) {
    \Drupal::database()
      ->query('DELETE FROM {' . $table . '} WHERE ' . $column . ' = 0');
  }
}

/**
 * Add harvest_days field to plant_type terms.
 */
function farm_plant_type_post_update_add_harvest_days(&$sandbox) {
  $field_storage = FieldStorageConfig::create([
    'id' => 'taxonomy_term.harvest_days',
    'field_name' => 'harvest_days',
    'entity_type' => 'taxonomy_term',
    'type' => 'integer',
    'settings' => [
      'unsigned' => TRUE,
      'size' => 'normal',
    ],
    'module' => 'core',
    'locked' => FALSE,
    'cardinality' => 1,
    'indexes' => [],
    'persist_with_no_fields' => FALSE,
    'custom_storage' => FALSE,
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_plant_type',
        ],
      ],
      'module' => [
        'taxonomy',
      ],
    ],
  ]);
  $field_storage->save();
  $field = FieldConfig::create([
    'id' => 'taxonomy_term.plant_type.harvest_days',
    'field_name' => 'harvest_days',
    'entity_type' => 'taxonomy_term',
    'bundle' => 'plant_type',
    'label' => 'Days of harvest',
    'description' => '',
    'required' => FALSE,
    'default_value' => [],
    'default_value_callback' => '',
    'settings' => [
      'min' => 1,
      'max' => NULL,
      'prefix' => '',
      'suffix' => ' day| days',
    ],
    'field_type' => 'integer',
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_plant_type',
        ],
      ],
      'module' => [
        'taxonomy',
      ],
    ],
  ]);
  $field->save();
}

/**
 * Move transplant_days field to farm_transplant module.
 */
function farm_plant_type_post_update_move_transplant_days() {

  // The transplant_days field was previously part of this module. It has moved
  // to the farm_transplanting module, so that it is only made available in
  // instances that deal with transplants. If there is transplant_days data in
  // the database, but the farm_transplant module is not installed, we should
  // install it so that module can be responsible for the data moving forward.
  $data_count = \Drupal::database()->query('SELECT COUNT(*) FROM {taxonomy_term__transplant_days}')->fetchField();
  if (!empty($data_count)) {
    if (!\Drupal::service('module_handler')->moduleExists('farm_transplanting')) {
      \Drupal::configFactory()->getEditable('field.field.taxonomy_term.plant_type.transplant_days')->delete();
      \Drupal::configFactory()->getEditable('field.storage.taxonomy_term.transplant_days')->delete();
      \Drupal::service('module_installer')->install(['farm_transplanting']);
    }
  }

  // Otherwise, we can delete the transplant_days field.
  // Using FieldConfig::load() and FieldStorageConfig::load() and their
  // associated delete() methods will delete the config and database tables.
  else {
    $field = FieldConfig::load('taxonomy_term.plant_type.transplant_days');
    if (!empty($field)) {
      $field->delete();
    }
    $field_storage = FieldStorageConfig::load('taxonomy_term.transplant_days');
    if (!empty($field_storage)) {
      $field_storage->delete();
    }
  }
}
