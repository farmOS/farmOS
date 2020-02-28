<?php
/**
 * @file
 * farmOS modules setup form.
 */

/**
 * Define farmOS modules that will be available to enable during setup.
 *
 * @return array
 *   Returns an array with two sub-arrays:
 *     'default': an array of modules that will be checked by default.
 *     'optional': an array of modules that will be unchecked by default.
 */
function farm_setup_modules() {
  return array(
    'default' => array(
      'farm_log_harvest' => t('Harvest logs'),
      'farm_log_input' => t('Input logs'),
      'farm_crop' => t('Crops'),
      'farm_livestock' => t('Livestock'),
      'farm_livestock_weight' => t('Livestock weight tracking'),
      'farm_equipment' => t('Equipment'),
      'farm_equipment_field' => t('Add "Equipment used" field to logs'),
      'farm_calendar' => t('Calendar of logs'),
      'farm_import' => t('CSV importers for assets and logs'),
      'farm_quick' => t('Quick forms UI'),
      'farm_soil_nrcs' => t('NRCS Soil Survey'),
      'farm_soil_test' => t('Soil test logs'),
      'farm_area_generate' => t('Area generator (for generating parallel beds within an area)'),
      'farm_area_import' => t('Import areas in bulk from a single KML file'),
      'farm_area_types' => t('Default area types: Property, Field, Building, etc'),
      'farm_crop_area_types' => t('Crop area types: Bed and Greenhouse'),
      'farm_livestock_area_types' => t('Livestock area types: Paddock'),
      'farm_water' => t('Water area type'),
      'farm_data_field' => t('Add an arbitrary "data" field to logs and assets'),
      'farm_access_roles' => t('Default roles: Manager, Worker, Viewer'),
      'farm_help' => t('farmOS Help Pages'),
      'farm_tour' => t('farmOS Tours'),
      'farm_api' => t('farmOS API'),
    ),
    'optional' => array(
      'farm_water_test' => t('Water test logs'),
      'farm_soil_compost' => t('Compost'),
      'farm_sensor' => t('Sensor'),
      'farm_sensor_listener' => t('Sensor: Listener'),
      'farm_ledger' => t('Sale and purchase logs (beta)'),
      'farm_quantity_report' => t('Quantity report generator (beta)'),
    ),
  );
}

/**
 * Form callback for the modules setup form.
 */
function farm_setup_modules_form($form, &$form_state) {
  $form = array();

  // Load the list of available modules.
  $modules = farm_setup_modules();

  // Allow user to choose which high-level farm modules to install.
  $module_options = array_merge($modules['default'], $modules['optional']);

  // Check which modules are already enabled.
  // These will be pre-selected and disabled
  // so they cannot be disabled.
  $enabled_modules = array();
  foreach ($module_options as $module => $module_name) {
    if (module_exists($module)) {
      $enabled_modules[] = $module;
    }
  }

  // Create a fieldset for the entire form
  $form['module_form'] = array(
    '#type' => 'fieldset',
    '#title' => t('Customize Modules'),
    '#collapsible' => TRUE,
  );

  // Module checkboxes.
  $form['module_form']['core_modules'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Core farmOS Modules'),
    '#description' => t('Select the farmOS modules that you would like to be enabled.'),
    '#options' => $module_options,
    '#default_value' => $enabled_modules,
  );

  // Disable checkboxes for modules that are already enabled.
  foreach ($enabled_modules as $module) {
    $form['module_form']['core_modules'][$module]['#disabled'] = TRUE;
  }

  // Load all modules.
  $files = system_rebuild_module_data();

  // Start arrays for farmOS Contrib modules.
  $module_options = array();
  $enabled_modules = array();

  // Check for farmOS Contrib modules.
  foreach ($files as $module) {
    // Only load modules in the farmOS Contrib package.
    if ($module->info['package'] == 'farmOS Contrib') {
      // Add to list of checkboxes.
      $module_options[$module->name] = $module->info['name'];

      // Check if the module is already enabled.
      if (module_exists($module->name)) {
        $enabled_modules[] = $module->name;
      }
    }
  }

  // farmOS Contrib Module checkboxes.
  $form['module_form']['contrib_modules'] = array(
    '#type' => 'checkboxes',
    '#title' => t('farmOS Contrib Modules'),
    '#description' => t('Select the farmOS Contrib modules that you would like to be enabled.'),
    '#options' => $module_options,
    '#default_value' => $enabled_modules,
  );

  // Disable checkboxes for contrib modules that are already enabled.
  foreach ($enabled_modules as $module) {
    $form['module_form']['contrib_modules'][$module]['#disabled'] = TRUE;
  }

  return $form;
}

/**
 * Submit callback for the modules setup form.
 */
function farm_setup_modules_form_submit($form, &$form_state) {

  // Get the selected modules.
  $core_modules = array_filter($form_state['values']['core_modules']);
  $contrib_modules = array_filter($form_state['values']['contrib_modules']);
  $modules = array_merge($core_modules, $contrib_modules);

  // Load list of module names from the system.
  $files = system_rebuild_module_data();

  // Start an array of batch operations.
  $operations = array();

  // Add operation to enable selected modules.
  foreach ($modules as $module => $enable) {
    if (!empty($enable)) {
      $operations[] = array('farm_setup_enable_module', array($module, $files[$module]->info['name']));
    }
  }

  // Create a batch if any operations are required.
  if (sizeof($operations) > 0 ) {
    // Assemble the Batch API.
    $batch = array(
      'title' => t('Installing optional modules'),
      // Provide an init_message to display when the batch starts.
      // This is better than the default 'initializing...' message that displays before
      // the first task has completed. Enabling modules can take some time so it is
      // good to tell the user to wait.
      'init_message' => t('Installing modules, please wait..'),
      'file' => drupal_get_path('module', 'farm_setup') . '/farm_setup.farm_setup.modules.inc',
      'operations' => $operations,
    );
    batch_set($batch);
  }

  // Display a success message on every form submit.
  drupal_set_message('Module settings saved.');
}

/**
 * BatchAPI callback: enable a module.
 *
 * @see farm_setup_modules_form_submit()
 */
function farm_setup_enable_module($module, $module_name, &$context) {
  if (!module_exists($module)) {
    module_enable(array($module));
  }
  $context['message'] = t('Installed %module module.', array('%module' => $module_name));
}
