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
      'farm_activity' => t('Activity logs'),
      'farm_observation' => t('Observation logs'),
      'farm_input' => t('Input logs'),
      'farm_harvest' => t('Harvest logs'),
    ],
    'optional' => [
      'farm_seeding' => t('Seeding logs'),
      'farm_transplanting' => t('Transplanting logs'),
      'farm_lab_test' => t('Lab test logs'),
      'farm_maintenance' => t('Maintenance logs'),
      'farm_medical' => t('Medical logs'),
      'farm_purchase' => t('Purchase logs'),
      'farm_sale' => t('Sale logs'),
    ],
  ];
}
