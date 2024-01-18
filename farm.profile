<?php

/**
 * @file
 * General functions for the farmOS installation profile.
 */

/**
 * Define farmOS modules that can be installed.
 *
 * @return array
 *   Returns an array with three sub-arrays: 'base', 'default' and 'optional'.
 *   Base modules will always be installed, but can be uninstalled. Default and
 *   optional modules will appear as options during farmOS installation and in
 *   a form available to admins. During initial farmOS installation, default
 *   modules will be selected by default, and optional modules will require the
 *   user to select them for installation.
 */
function farm_modules() {
  return [
    'base' => [
      'farm_api' => t('farmOS API'),
      'farm_login' => t('Login with username or email.'),
      'farm_settings' => t('farmOS Settings forms'),
      'farm_setup' => t('farmOS Setup pages'),
      'farm_ui' => t('farmOS UI'),
      'farm_update' => t('farmOS Update'),
    ],
    'default' => [
      'farm_land' => t('Land assets'),
      'farm_plant' => t('Plant assets'),
      'farm_animal' => t('Animal assets'),
      'farm_equipment' => t('Equipment assets'),
      'farm_structure' => t('Structure assets'),
      'farm_water' => t('Water assets'),
      'farm_activity' => t('Activity logs'),
      'farm_observation' => t('Observation logs'),
      'farm_seeding' => t('Seeding logs'),
      'farm_input' => t('Input logs'),
      'farm_harvest' => t('Harvest logs'),
      'farm_maintenance' => t('Maintenance logs'),
      'farm_quantity_standard' => t('Standard quantity type'),
      'farm_role_roles' => t('Default roles: Manager, Worker, Viewer'),
      'farm_land_types' => t('Default land types: Property, Field, Bed, Paddock, Landmark'),
      'farm_structure_types' => t('Default structure types: Building, Greenhouse'),
    ],
    'optional' => [
      'farm_inventory' => t('Inventory management'),
      'farm_material' => t('Material assets'),
      'farm_seed' => t('Seed assets'),
      'farm_product' => t('Product assets'),
      'farm_sensor' => t('Sensor assets'),
      'farm_compost' => t('Compost assets'),
      'farm_group' => t('Group assets'),
      'farm_transplanting' => t('Transplanting logs'),
      'farm_lab_test' => t('Lab test logs'),
      'farm_birth' => t('Birth logs'),
      'farm_medical' => t('Medical logs'),
      'farm_export_csv' => t('CSV exporter'),
      'farm_import_csv' => t('CSV importer'),
      'farm_export_kml' => t('KML exporter'),
      'farm_import_kml' => t('KML asset importer'),
      'farm_map_mapbox' => t('Mapbox map layers: Satellite, Outdoors'),
      'farm_api_default_consumer' => t('Default API Consumer'),
      'farm_fieldkit' => t('Field Kit integration'),
      'farm_l10n' => t('Translation/localization features'),
      'farm_role_account_admin' => t('Account Admin role'),
    ],
  ];
}

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function farm_form_update_manager_update_form_alter(&$form, &$form_state, $form_id) {

  // Disable updating through the UI.
  // @see https://www.drupal.org/project/farm/issues/3136140
  $message = t('Performing updates through this interface is disabled by farmOS. To update modules, use a packaged release of farmOS to ensure that any necessary patches are applied to dependencies.');
  \Drupal::messenger()->addError($message);
  $form['actions']['#access'] = FALSE;
}
