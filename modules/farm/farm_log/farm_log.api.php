<?php

/**
 * @file
 * Hooks provided by farm_log.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_log Farm log module integrations.
 *
 * Module integrations with the farm_log module.
 */

/**
 * @defgroup farm_log_hooks Farm log's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_log.
 */

/**
 * Provide a list of log categories that should be created when the module
 * is installed. Note that these will be passed through the t() function when
 * they are created so that they can be translated. This does mean that they
 * will only be translated once, to whatever the site's default language is.
 *
 * @return array
 *   Returns an array of log categories (as simple strings).
 */
function hook_farm_log_categories() {
  return array(
    'My module category',
    'My other category',
  );
}

/**
 * Allow modules to automatically populate log categories in log forms. The
 * category must exist already. Note that these will be passed through the t()
 * function when they are added so that they can be translated. This does mean
 * that they will only be translated once, to whatever the site's default
 * language is.
 *
 * @param object $log
 *   A log entity.
 *
 * @return array
 *   Returns an array of log categories (as simple strings).
 */
function hook_farm_log_categories_populate($log) {
  $categories = array();

  if ($log->type == 'farm_water_test') {
    $categories[] = 'Water';
  }

  return $categories;
}

/**
 * Allow modules to provide information about fields that should be
 * prepopulated in log forms.
 *
 * @return array
 *   Returns an array of field information.
 */
function hook_farm_log_prepopulate_reference_fields() {
  return array(
    'field_farm_asset' => array(
      'entity_type' => 'farm_asset',
      'url_param' => 'farm_asset',
    ),
  );
}

/**
 * @}
 */
