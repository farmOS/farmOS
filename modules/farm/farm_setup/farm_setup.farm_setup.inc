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
    'modules' => array(
      'label' => t('Modules'),
      'form' => 'farm_setup_modules_form',
      'file' => 'farm_setup.farm_setup.modules.inc',
    ),
  );
}
