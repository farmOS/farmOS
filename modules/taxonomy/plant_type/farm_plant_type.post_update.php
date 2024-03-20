<?php

/**
 * @file
 * Post update hooks for the farm_plant_type module.
 */

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

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
