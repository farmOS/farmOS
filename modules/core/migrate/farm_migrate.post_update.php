<?php

/**
 * @file
 * Post update hooks for the farm_migrate module.
 */

/**
 * Uninstall farmOS v1 migrations.
 */
function farm_migrate_post_update_uninstall_v1_migrations(&$sandbox) {

  // Delete migration and migration_group configurations.
  $configurations = [
    'migrate_plus.migration.farm_migrate_area_land',
    'migrate_plus.migration.farm_migrate_area_none',
    'migrate_plus.migration.farm_migrate_area_structure',
    'migrate_plus.migration.farm_migrate_area_water',
    'migrate_plus.migration.farm_migrate_asset_animal',
    'migrate_plus.migration.farm_migrate_asset_compost',
    'migrate_plus.migration.farm_migrate_asset_equipment',
    'migrate_plus.migration.farm_migrate_asset_group',
    'migrate_plus.migration.farm_migrate_asset_plant',
    'migrate_plus.migration.farm_migrate_asset_sensor',
    'migrate_plus.migration.farm_migrate_asset_sensor_listener',
    'migrate_plus.migration.farm_migrate_inventory',
    'migrate_plus.migration.farm_migrate_log_activity',
    'migrate_plus.migration.farm_migrate_log_birth',
    'migrate_plus.migration.farm_migrate_log_harvest',
    'migrate_plus.migration.farm_migrate_log_input',
    'migrate_plus.migration.farm_migrate_log_lab_test',
    'migrate_plus.migration.farm_migrate_log_maintenance',
    'migrate_plus.migration.farm_migrate_log_medical',
    'migrate_plus.migration.farm_migrate_log_observation',
    'migrate_plus.migration.farm_migrate_log_seeding',
    'migrate_plus.migration.farm_migrate_log_transplanting',
    'migrate_plus.migration.farm_migrate_quantity_standard',
    'migrate_plus.migration.farm_migrate_role',
    'migrate_plus.migration.farm_migrate_sensor_listener_data_streams',
    'migrate_plus.migration.farm_migrate_sensor_listener_notifications',
    'migrate_plus.migration.farm_migrate_taxonomy_animal_type',
    'migrate_plus.migration.farm_migrate_taxonomy_crop_family',
    'migrate_plus.migration.farm_migrate_taxonomy_log_category',
    'migrate_plus.migration.farm_migrate_taxonomy_material_type',
    'migrate_plus.migration.farm_migrate_taxonomy_plant_type',
    'migrate_plus.migration.farm_migrate_taxonomy_season',
    'migrate_plus.migration.farm_migrate_taxonomy_unit',
    'migrate_plus.migration.farm_migrate_area_field_parent',
    'migrate_plus.migration.farm_migrate_asset_field_parent',
    'migrate_plus.migration.farm_migrate_file',
    'migrate_plus.migration.farm_migrate_file_private',
    'migrate_plus.migration.farm_migrate_quantity_system',
    'migrate_plus.migration.farm_migrate_system_date',
    'migrate_plus.migration.farm_migrate_user',
    'migrate_plus.migration_group.farm_migrate_area',
    'migrate_plus.migration_group.farm_migrate_asset',
    'migrate_plus.migration_group.farm_migrate_asset_parent',
    'migrate_plus.migration_group.farm_migrate_config',
    'migrate_plus.migration_group.farm_migrate_file',
    'migrate_plus.migration_group.farm_migrate_log',
    'migrate_plus.migration_group.farm_migrate_plan',
    'migrate_plus.migration_group.farm_migrate_quantity',
    'migrate_plus.migration_group.farm_migrate_role',
    'migrate_plus.migration_group.farm_migrate_sensor_data',
    'migrate_plus.migration_group.farm_migrate_taxonomy',
    'migrate_plus.migration_group.farm_migrate_user',
  ];
  foreach ($configurations as $name) {
    \Drupal::configFactory()->getEditable($name)->delete();
  }

  // Uninstall the migrate_drupal module.
  if (\Drupal::service('module_handler')->moduleExists('migrate_drupal')) {
    \Drupal::service('module_installer')->uninstall(['migrate_drupal']);
  }
}
