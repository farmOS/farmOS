<?php
/**
 * @file
 * farm_manager.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function farm_manager_user_default_permissions() {
  $permissions = array();

  // Farm Area permissions.
  if (module_exists('farm_area')) {

    // Exported permission: delete terms in farm_materials.
    $permissions['delete terms in farm_areas'] = array(
      'name' => 'delete terms in farm_areas',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_materials.
    $permissions['edit terms in farm_areas'] = array(
      'name' => 'edit terms in farm_areas',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );
  }

  // Farm Taxonomy permissions.
  if (module_exists('farm_taxonomy')) {

    // Exported permission: delete terms in farm_materials.
    $permissions['delete terms in farm_materials'] = array(
      'name' => 'delete terms in farm_materials',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: delete terms in farm_season.
    $permissions['delete terms in farm_season'] = array(
      'name' => 'delete terms in farm_season',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_materials.
    $permissions['edit terms in farm_materials'] = array(
      'name' => 'edit terms in farm_materials',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_season.
    $permissions['edit terms in farm_season'] = array(
      'name' => 'edit terms in farm_season',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );
  }

  // Farm Crop permissions.
  if (module_exists('farm_crop')) {

    // Exported permission: create farm_harvest log entities.
    $permissions['create farm_harvest log entities'] = array(
      'name' => 'create farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create farm_input log entities.
    $permissions['create farm_input log entities'] = array(
      'name' => 'create farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create farm_seeding log entities.
    $permissions['create farm_seeding log entities'] = array(
      'name' => 'create farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create farm_transplanting log entities.
    $permissions['create farm_transplanting log entities'] = array(
      'name' => 'create farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create planting farm assets.
    $permissions['create planting farm assets'] = array(
      'name' => 'create planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete any farm_harvest log entities.
    $permissions['delete any farm_harvest log entities'] = array(
      'name' => 'delete any farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_input log entities.
    $permissions['delete any farm_input log entities'] = array(
      'name' => 'delete any farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_seeding log entities.
    $permissions['delete any farm_seeding log entities'] = array(
      'name' => 'delete any farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_transplanting log entities.
    $permissions['delete any farm_transplanting log entities'] = array(
      'name' => 'delete any farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any planting farm assets.
    $permissions['delete any planting farm assets'] = array(
      'name' => 'delete any planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete own farm_harvest log entities.
    $permissions['delete own farm_harvest log entities'] = array(
      'name' => 'delete own farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_input log entities.
    $permissions['delete own farm_input log entities'] = array(
      'name' => 'delete own farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_seeding log entities.
    $permissions['delete own farm_seeding log entities'] = array(
      'name' => 'delete own farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_transplanting log entities.
    $permissions['delete own farm_transplanting log entities'] = array(
      'name' => 'delete own farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own planting farm assets.
    $permissions['delete own planting farm assets'] = array(
      'name' => 'delete own planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete terms in farm_crop_families.
    $permissions['delete terms in farm_crop_families'] = array(
      'name' => 'delete terms in farm_crop_families',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: delete terms in farm_crops.
    $permissions['delete terms in farm_crops'] = array(
      'name' => 'delete terms in farm_crops',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit any farm_harvest log entities.
    $permissions['edit any farm_harvest log entities'] = array(
      'name' => 'edit any farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any farm_input log entities.
    $permissions['edit any farm_input log entities'] = array(
      'name' => 'edit any farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any farm_seeding log entities.
    $permissions['edit any farm_seeding log entities'] = array(
      'name' => 'edit any farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any farm_transplanting log entities.
    $permissions['edit any farm_transplanting log entities'] = array(
      'name' => 'edit any farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any planting farm assets.
    $permissions['edit any planting farm assets'] = array(
      'name' => 'edit any planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit own farm_harvest log entities.
    $permissions['edit own farm_harvest log entities'] = array(
      'name' => 'edit own farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_input log entities.
    $permissions['edit own farm_input log entities'] = array(
      'name' => 'edit own farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_seeding log entities.
    $permissions['edit own farm_seeding log entities'] = array(
      'name' => 'edit own farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_transplanting log entities.
    $permissions['edit own farm_transplanting log entities'] = array(
      'name' => 'edit own farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own planting farm assets.
    $permissions['edit own planting farm assets'] = array(
      'name' => 'edit own planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit terms in farm_crop_families.
    $permissions['edit terms in farm_crop_families'] = array(
      'name' => 'edit terms in farm_crop_families',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_crops.
    $permissions['edit terms in farm_crops'] = array(
      'name' => 'edit terms in farm_crops',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: view any farm_harvest log entities.
    $permissions['view any farm_harvest log entities'] = array(
      'name' => 'view any farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any farm_input log entities.
    $permissions['view any farm_input log entities'] = array(
      'name' => 'view any farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any farm_seeding log entities.
    $permissions['view any farm_seeding log entities'] = array(
      'name' => 'view any farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any farm_transplanting log entities.
    $permissions['view any farm_transplanting log entities'] = array(
      'name' => 'view any farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any planting farm assets.
    $permissions['view any planting farm assets'] = array(
      'name' => 'view any planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: view own farm_harvest log entities.
    $permissions['view own farm_harvest log entities'] = array(
      'name' => 'view own farm_harvest log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own farm_input log entities.
    $permissions['view own farm_input log entities'] = array(
      'name' => 'view own farm_input log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own farm_seeding log entities.
    $permissions['view own farm_seeding log entities'] = array(
      'name' => 'view own farm_seeding log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own farm_transplanting log entities.
    $permissions['view own farm_transplanting log entities'] = array(
      'name' => 'view own farm_transplanting log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own planting farm assets.
    $permissions['view own planting farm assets'] = array(
      'name' => 'view own planting farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );
  }

  // Farm Equipment permissions.
  if (module_exists('farm_equipment')) {

    // Exported permission: create equipment farm assets.
    $permissions['create equipment farm assets'] = array(
      'name' => 'create equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete any equipment farm assets.
    $permissions['delete any equipment farm assets'] = array(
      'name' => 'delete any equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete own equipment farm assets.
    $permissions['delete own equipment farm assets'] = array(
      'name' => 'delete own equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit any equipment farm assets.
    $permissions['edit any equipment farm assets'] = array(
      'name' => 'edit any equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit own equipment farm assets.
    $permissions['edit own equipment farm assets'] = array(
      'name' => 'edit own equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: view any equipment farm assets.
    $permissions['view any equipment farm assets'] = array(
      'name' => 'view any equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: view own equipment farm assets.
    $permissions['view own equipment farm assets'] = array(
      'name' => 'view own equipment farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );
  }

  // Farm Livestock permissions.
  if (module_exists('farm_livestock')) {

    // Exported permission: create animal farm assets.
    $permissions['create animal farm assets'] = array(
      'name' => 'create animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: create farm_eggs log entities.
    $permissions['create farm_eggs log entities'] = array(
      'name' => 'create farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any animal farm assets.
    $permissions['delete any animal farm assets'] = array(
      'name' => 'delete any animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete any farm_eggs log entities.
    $permissions['delete any farm_eggs log entities'] = array(
      'name' => 'delete any farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own animal farm assets.
    $permissions['delete own animal farm assets'] = array(
      'name' => 'delete own animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: delete own farm_eggs log entities.
    $permissions['delete own farm_eggs log entities'] = array(
      'name' => 'delete own farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete terms in farm_animal_groups.
    $permissions['delete terms in farm_animal_groups'] = array(
      'name' => 'delete terms in farm_animal_groups',
      'roles' => array(),
      'module' => 'taxonomy',
    );

    // Exported permission: delete terms in farm_animal_types.
    $permissions['delete terms in farm_animal_types'] = array(
      'name' => 'delete terms in farm_animal_types',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit any animal farm assets.
    $permissions['edit any animal farm assets'] = array(
      'name' => 'edit any animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit any farm_eggs log entities.
    $permissions['edit any farm_eggs log entities'] = array(
      'name' => 'edit any farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own animal farm assets.
    $permissions['edit own animal farm assets'] = array(
      'name' => 'edit own animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: edit own farm_eggs log entities.
    $permissions['edit own farm_eggs log entities'] = array(
      'name' => 'edit own farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit terms in farm_animal_groups.
    $permissions['edit terms in farm_animal_groups'] = array(
      'name' => 'edit terms in farm_animal_groups',
      'roles' => array(),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_animal_types.
    $permissions['edit terms in farm_animal_types'] = array(
      'name' => 'edit terms in farm_animal_types',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: view any animal farm assets.
    $permissions['view any animal farm assets'] = array(
      'name' => 'view any animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: view any farm_eggs log entities.
    $permissions['view any farm_eggs log entities'] = array(
      'name' => 'view any farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own animal farm assets.
    $permissions['view own animal farm assets'] = array(
      'name' => 'view own animal farm assets',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_asset',
    );

    // Exported permission: view own farm_eggs log entities.
    $permissions['view own farm_eggs log entities'] = array(
      'name' => 'view own farm_eggs log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );
  }

  // Farm Log permissions.
  if (module_exists('farm_log')) {

    // Exported permission: create farm_activity log entities.
    $permissions['create farm_activity log entities'] = array(
      'name' => 'create farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create farm_issue log entities.
    $permissions['create farm_issue log entities'] = array(
      'name' => 'create farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: create farm_movement log entities.
    $permissions['create farm_movement log entities'] = array(
      'name' => 'create farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_activity log entities.
    $permissions['delete any farm_activity log entities'] = array(
      'name' => 'delete any farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_issue log entities.
    $permissions['delete any farm_issue log entities'] = array(
      'name' => 'delete any farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete any farm_movement log entities.
    $permissions['delete any farm_movement log entities'] = array(
      'name' => 'delete any farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_activity log entities.
    $permissions['delete own farm_activity log entities'] = array(
      'name' => 'delete own farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_issue log entities.
    $permissions['delete own farm_issue log entities'] = array(
      'name' => 'delete own farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete own farm_movement log entities.
    $permissions['delete own farm_movement log entities'] = array(
      'name' => 'delete own farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: delete terms in farm_priority.
    $permissions['delete terms in farm_priority'] = array(
      'name' => 'delete terms in farm_priority',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: delete terms in farm_quantity_units.
    $permissions['delete terms in farm_quantity_units'] = array(
      'name' => 'delete terms in farm_quantity_units',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit any farm_activity log entities.
    $permissions['edit any farm_activity log entities'] = array(
      'name' => 'edit any farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any farm_issue log entities.
    $permissions['edit any farm_issue log entities'] = array(
      'name' => 'edit any farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit any farm_movement log entities.
    $permissions['edit any farm_movement log entities'] = array(
      'name' => 'edit any farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_activity log entities.
    $permissions['edit own farm_activity log entities'] = array(
      'name' => 'edit own farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_issue log entities.
    $permissions['edit own farm_issue log entities'] = array(
      'name' => 'edit own farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit own farm_movement log entities.
    $permissions['edit own farm_movement log entities'] = array(
      'name' => 'edit own farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: edit terms in farm_priority.
    $permissions['edit terms in farm_priority'] = array(
      'name' => 'edit terms in farm_priority',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: edit terms in farm_quantity_units.
    $permissions['edit terms in farm_quantity_units'] = array(
      'name' => 'edit terms in farm_quantity_units',
      'roles' => array(
        0 => 'Administrator',
        1 => 'Farm Manager',
      ),
      'module' => 'taxonomy',
    );

    // Exported permission: view any farm_activity log entities.
    $permissions['view any farm_activity log entities'] = array(
      'name' => 'view any farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any farm_issue log entities.
    $permissions['view any farm_issue log entities'] = array(
      'name' => 'view any farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view any farm_movement log entities.
    $permissions['view any farm_movement log entities'] = array(
      'name' => 'view any farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view farm logs.
    $permissions['view farm logs'] = array(
      'name' => 'view farm logs',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'farm_log',
    );

    // Exported permission: view own farm_activity log entities.
    $permissions['view own farm_activity log entities'] = array(
      'name' => 'view own farm_activity log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own farm_issue log entities.
    $permissions['view own farm_issue log entities'] = array(
      'name' => 'view own farm_issue log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );

    // Exported permission: view own farm_movement log entities.
    $permissions['view own farm_movement log entities'] = array(
      'name' => 'view own farm_movement log entities',
      'roles' => array(
        0 => 'Farm Manager',
      ),
      'module' => 'log',
    );
  }

  return $permissions;
}
