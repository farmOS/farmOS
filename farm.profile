<?php

/**
 * @file
 * FarmOS installation profile
 */

/**
 * Implements hook_install_tasks().
 */
function farm_install_tasks($install_state) {
  $tasks = array();
  $tasks['farm_configure_themes'] = array(
    'display_name' => st('Farm: configure themes'),
    'display' => FALSE,
    'type' => 'normal',
    'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    'function' => 'farm_configure_themes',
  );
  $tasks['farm_configure_front'] = array(
    'display_name' => st('Farm: configure front page'),
    'display' => FALSE,
    'type' => 'normal',
    'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
    'function' => 'farm_configure_front',
  );
  return $tasks;
}

/**
 * Set default themes. Disable Bartik.
 */
function farm_configure_themes() {

  // Any themes without keys here will get numeric keys and so will be enabled,
  // but not placed into variables.
  $enable = array(
    'theme_default' => 'bootstrap',
    'admin_theme' => 'bootstrap',
  );
  theme_enable($enable);

  // Create variables for each theme.
  foreach ($enable as $var => $theme) {
    if (!is_numeric($var)) {
      variable_set($var, $theme);
    }
  }

  // Disable the default Bartik theme
  theme_disable(array('bartik'));
}

/**
 * Set the front page of the site.
 */
function farm_configure_front() {

  // Set the front page to the farm dashboard provided by the farm_admin module.
  variable_set('site_frontpage', 'farm');
}
