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
  $perms = array(
    'delete terms in farm_areas',
    'edit terms in farm_areas',
  );
  return $perms;
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_taxonomy_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_taxonomy module.
  $perms = array(
    'delete terms in farm_materials',
    'delete terms in farm_season',
    'edit terms in farm_materials',
    'edit terms in farm_season',
  );
  return $perms;
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_crop_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_crop module.
  $perms = array(
    'create farm_harvest log entities',
    'create farm_input log entities',
    'create farm_seeding log entities',
    'create farm_transplanting log entities',
    'create planting farm assets',
    'delete any farm_harvest log entities',
    'delete any farm_input log entities',
    'delete any farm_seeding log entities',
    'delete any farm_transplanting log entities',
    'delete any planting farm assets',
    'delete own farm_harvest log entities',
    'delete own farm_input log entities',
    'delete own farm_seeding log entities',
    'delete own farm_transplanting log entities',
    'delete own planting farm assets',
    'delete terms in farm_crop_families',
    'delete terms in farm_crops',
    'edit any farm_harvest log entities',
    'edit any farm_input log entities',
    'edit any farm_seeding log entities',
    'edit any farm_transplanting log entities',
    'edit any planting farm assets',
    'edit own farm_harvest log entities',
    'edit own farm_input log entities',
    'edit own farm_seeding log entities',
    'edit own farm_transplanting log entities',
    'edit own planting farm assets',
    'edit terms in farm_crop_families',
    'edit terms in farm_crops',
    'view any farm_harvest log entities',
    'view any farm_input log entities',
    'view any farm_seeding log entities',
    'view any farm_transplanting log entities',
    'view any planting farm assets',
    'view own farm_harvest log entities',
    'view own farm_input log entities',
    'view own farm_seeding log entities',
    'view own farm_transplanting log entities',
    'view own planting farm assets',
  );
  return $perms;
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_equipment_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_equipment module.
  $perms = array(
    'create equipment farm assets',
    'delete any equipment farm assets',
    'delete own equipment farm assets',
    'edit any equipment farm assets',
    'edit own equipment farm assets',
    'view any equipment farm assets',
    'view own equipment farm assets',
  );
  return $perms;
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_livestock_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_livestock module.
  $perms = array(
    'create animal farm assets',
    'create farm_eggs log entities',
    'delete any animal farm assets',
    'delete any farm_eggs log entities',
    'delete own animal farm assets',
    'delete own farm_eggs log entities',
    'delete terms in farm_animal_groups',
    'delete terms in farm_animal_types',
    'edit any animal farm assets',
    'edit any farm_eggs log entities',
    'edit own animal farm assets',
    'edit own farm_eggs log entities',
    'edit terms in farm_animal_groups',
    'edit terms in farm_animal_types',
    'view any animal farm assets',
    'view any farm_eggs log entities',
    'view own animal farm assets',
    'view own farm_eggs log entities',
  );
  return $perms;
}

/**
 * Implements hook_farm_manager_permissions().
 */
function farm_log_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_log module.
  $perms = array(
    'create farm_activity log entities',
    'create farm_issue log entities',
    'create farm_movement log entities',
    'delete any farm_activity log entities',
    'delete any farm_issue log entities',
    'delete any farm_movement log entities',
    'delete own farm_activity log entities',
    'delete own farm_issue log entities',
    'delete own farm_movement log entities',
    'delete terms in farm_priority',
    'delete terms in farm_quantity_units',
    'edit any farm_activity log entities',
    'edit any farm_issue log entities',
    'edit any farm_movement log entities',
    'edit own farm_activity log entities',
    'edit own farm_issue log entities',
    'edit own farm_movement log entities',
    'edit terms in farm_priority',
    'edit terms in farm_quantity_units',
    'view any farm_activity log entities',
    'view any farm_issue log entities',
    'view any farm_movement log entities',
    'view farm logs',
    'view own farm_activity log entities',
    'view own farm_issue log entities',
    'view own farm_movement log entities',
  );
  return $perms;
}