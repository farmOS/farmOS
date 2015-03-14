<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */


/**
 * @defgroup farm_admin Farm admin module integrations.
 *
 * Module integrations with the farm_admin module.
 */

/**
 * @defgroup farm_admin_hooks Farm admin's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_admin.
 */

/**
 * Provide links for farm admins.
 *
 * @return array
 *   Returns an array of actions and their meta information (see example below).
 */
function hook_farm_admin_actions() {

  // Define farm area actions.
  $actions = array(
    'foo' => array(
      'title' => t('Add a foo log'),
      'href' => 'log/add/farm_foo',
      'paths' => array(
        'farm/asset/%/foo',
      ),
      'assets' => array(
        'bar',
      ),
      'views' => array(
        'foo_view',
      ),
    ),
  );
  return $actions;
}

/**
 * @}
 */
