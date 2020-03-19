<?php

/**
 * @file
 * Farm Setup hooks implemented by the Farm Setup module.
 */

/**
 * Implements hook_farm_setup_forms().
 */
function farm_setup_farm_setup_forms() {
  return array(
    'start' => array(
      'label' => t('Start'),
      'form' => 'farm_setup_start_form',
      'include_file' => 'farm_setup.farm_setup',
      'display' => array(
        'setup page' => FALSE,
      ),
      'weight' => -100,
    ),
    'finish' => array(
      'label' => t('Finished'),
      'form' => 'farm_setup_finish_form',
      'include_file' => 'farm_setup.farm_setup',
      'weight' => 100,
    ),
    'modules' => array(
      'label' => t('Modules'),
      'form' => 'farm_setup_modules_form',
      'include_file' => 'farm_setup.farm_setup.modules',
      'weight' => 0,
    ),
    'timezone' => array(
      'label' => t('Default Timezone'),
      'form' => 'farm_setup_timezone_form',
      'include_file' => 'farm_setup.farm_setup.timezone',
      'display' => array(
        'setup page' => FALSE,
      ),
      'weight' => 0,
    ),
  );
}


/**
 * Start form callback.
 */
function farm_setup_start_form($form, &$form_state) {
  $form['info'] = array(
    '#markup' => '<p>' . t('This setup wizard will guide you through the process of configuring your farmOS.') . '</p>',
  );

  $form['start'] = array(
    '#type' => 'submit',
    '#value' => t('Start'),
    '#submit' => array('farm_setup_wizard_form_next_submit'),
  );

  return $form;
}

/**
 * Form callback for the "more" setup form.
 */
function farm_setup_finish_form($form, &$form_state) {
  $form = array();

  $form['more'] = array(
    '#type' => 'vertical_tabs',
    '#title' => t('Additional Settings'),
  );

  $form['more']['settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Additional Settings'),
  );

  $form['more']['settings']['description'] = array(
    '#markup' => '<h3>Additional Settings</h3><div>These are some additional settings that can be used to customize your farmOS installation. You can revisit these settings at any time.</div>',
  );

  $form['more']['taxonomies'] = array(
    '#type' => 'fieldset',
    '#title' => t('Taxonomies'),
  );

  $form['more']['taxonomies']['description'] = array(
    '#markup' => '<h4>Customize the farmOS Vocabulary</h4><div>Taxonomies are used to organize farmOS records. They are grouped into several vocabularies that you can edit below:</div><br>',
  );

  $taxonomies = array(
    'farm_animal_types' => array(
      'module' => 'farm_livestock',
      'label' => t('Farm Animal Species/Breeds'),
      'description' => t('Animal species and breeds that are used in farmOS'),
    ),
    'farm_crop_families' => array(
      'module' => 'farm_crop',
      'label' => t('Farm Crop Families'),
      'description' => t('Used to categorize farm crops and varieties.'),
    ),
    'farm_crops' => array(
      'module' => 'farm_crop',
      'label' => t('Farm Crops/Varieties'),
      'description' => t('Farm crops and varieties used in Planting assets.'),
    ),
    'farm_log_categories' => array(
      'module' => 'farm_log',
      'label' => t('Farm Log Categories'),
      'description' => t('Used to organize any type of log in farmOS.'),
    ),
    'farm_quantity_units' => array(
      'module' => 'farm_quantity',
      'label' => t('Farm Quantity Units'),
      'description' => t('Used as the unit for any quantity associated with logs.'),
    )
  );

  foreach ($taxonomies as $name => $vocabulary) {
    if (module_exists($vocabulary['module'])) {
      $path = 'admin/structure/taxonomy/' . $name;
      $form['more']['taxonomies'][$name] = array(
        '#markup' => '<div><strong>' . l($vocabulary['label'], $path) . '</strong>: ' . $vocabulary['description'] . '</div>',
      );
    }
  }

  return $form;
}
