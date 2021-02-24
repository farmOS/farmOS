<?php

/**
 * @file
 * General functions for the farmOS installation profile.
 */

/**
 * Define farmOS modules that can be installed.
 *
 * @return array
 *   Returns an array with two sub-arrays: 'default' and 'optional'. Default
 *   modules will be selected for installation by default, and optional modules
 *   will require the user to select them for installation.
 */
function farm_modules() {
  return [
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
      'farm_land_types' => t('Default land types: Property, Field, Bed, Paddock, Landmark, Other'),
      'farm_structure_types' => t('Default structure types: Building, Greenhouse'),
      'farm_login' => t('Login with username or email.'),
      'farm_api' => t('farmOS API'),
      'farm_dashboard' => t('farmOS Dashboard'),
      'farm_ui' => t('farmOS UI'),
    ],
    'optional' => [
      'farm_sensor' => t('Sensor assets'),
      'farm_compost' => t('Compost assets'),
      'farm_group' => t('Group assets'),
      'farm_transplanting' => t('Transplanting logs'),
      'farm_lab_test' => t('Lab test logs'),
      'farm_medical' => t('Medical logs'),
      'farm_purchase' => t('Purchase logs'),
      'farm_sale' => t('Sale logs'),
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
