<?php
/**
 * @file
 * Farm Manager module permissions
 */

/**
 * Implements hook_farm_manager_permissions().
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
 * Implements hook_farm_manager_permissions().
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
 * Implements hook_farm_manager_permissions().
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
 * Implements hook_farm_manager_permissions().
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
 * Implements hook_farm_manager_permissions().
 */
function farm_livestock_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_livestock module.
  $types = array(
    'farm_asset' => array(
      'animal',
    ),
    'log' => array(
      'farm_eggs',
    ),
    'taxonomy' => array(
      'farm_animal_groups',
      'farm_animal_types',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_log_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_log module.
  $types = array(
    'log' => array(
      'farm_activity',
      'farm_issue',
      'farm_movement',
    ),
    'taxonomy' => array(
      'farm_priority',
      'farm_quantity_units',
    ),
  );
  $perms = farm_manager_entity_perms($types);

  // Add 'view farm logs' permission.
  $perms[] = 'view farm logs';

  return $perms;
}