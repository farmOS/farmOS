<?php

/**
 * @file
 * Hooks provided by farm_setup.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_setup Farm setup module integrations.
 *
 * Module integrations with the farm_setup module.
 */

/**
 * @defgroup farm_setup_hooks Farm setup's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_setup.
 */

/**
 * Define setup forms provided by this module.
 */
function hook_farm_setup_forms() {
  return array(
    'myform' => array(

      // This will be displayed as the title of setup form.
      'label' => t('My form'),

      // The form callback function.
      'form' => 'my_setup_form',

      // If the setup form functions are stored in a separate PHP file, specify
      // that as follows (relative to the module's directory).
      'file' => 'mymodule.farm_setup.inc',

      // The weight of the form that will be used in determining where it falls
      // in the multi-step setup process. A lower weight will appear earlier.
      'weight' => 10,
    ),
  );
}

/**
 * @}
 */
