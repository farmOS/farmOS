<?php
/**
 * @file
 * Farm Manager module permissions.
 */

/**
 * Implements hook_farm_manager_perms().
 */
function farm_admin_farm_manager_perms() {
  $perms = array();

  // Access farm dashboard.
  $perms[] = 'access farm dashboard';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_area_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_area module.
  $types = array(
    'taxonomy' => array(
      'farm_areas',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_taxonomy_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_taxonomy module.
  $types = array(
    'taxonomy' => array(
      'farm_materials',
      'farm_season',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_crop_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_crop module.
  $types = array(
    'farm_asset' => array(
      'planting',
    ),
    'log' => array(
      'farm_harvest',
      'farm_input',
      'farm_seeding',
      'farm_transplanting',
    ),
    'taxonomy' => array(
      'farm_crops',
      'farm_crop_families',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_equipment_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_equipment module.
  $types = array(
    'farm_asset' => array(
      'equipment',
    ),
    'log' => array(
      'farm_maintenance',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_livestock_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_livestock module.
  $types = array(
    'farm_asset' => array(
      'animal',
    ),
    'taxonomy' => array(
      'farm_animal_groups',
      'farm_animal_types',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_livestock_eggs_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_livestock module.
  $types = array(
    'log' => array(
      'farm_eggs',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_log_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_log module.
  $types = array(
    'log' => array(
      'farm_activity',
      'farm_movement',
      'farm_observation',
    ),
    'taxonomy' => array(
      'farm_observation_types',
      'farm_priority',
      'farm_quantity_units',
    ),
  );
  $perms = farm_manager_entity_perms($types);

  // Add 'view farm logs' permission.
  $perms[] = 'view farm logs';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function farm_soil_test_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_log module.
  $types = array(
    'log' => array(
      'farm_soil_test',
    ),
    'taxonomy' => array(
      'farm_soil_types',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_perms().
 */
function log_farm_manager_perms() {
  $perms = array();

  // Access farm dashboard.
  $perms[] = 'view log plan';

  return $perms;
}
