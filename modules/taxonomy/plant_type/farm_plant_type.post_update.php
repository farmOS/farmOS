<?php

/**
 * @file
 * Post update hooks for the farm_plant_type module.
 */

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\field\Entity\FieldConfig;

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
    \Drupal::database()->query('DELETE FROM {' . $table . '} WHERE ' . $column . ' = 0');
  }
}
