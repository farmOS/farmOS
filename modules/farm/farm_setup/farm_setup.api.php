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

      // If the setup form functions are stored in a separate include file,
      // specify the name of the file WIHTOUT the .inc ending.
      'include_file' => 'mymodule.farm_setup',

      // Specify where to display the setup form. All locations are TRUE
      // by default. Thus this attribute is only required if you want to
      // hide a setup form from any display location.
      // Possible displays:
      //   'wizard' = The step-by-step setup wizard run on first install.
      //   'setup page' = The setup page available at /farm/setup.
      'display' => array(
        'wizard' => FALSE,
        'setup page' => FALSE,
      ),

      // The weight of the form that will be used in determining where it falls
      // in the multi-step setup process. A lower weight will appear earlier.
      //  Weights 0-10 are reserved for forms supplied via the Farm Setup module.
      'weight' => 11,
    ),
  );
}

/**
 * @}
 */
