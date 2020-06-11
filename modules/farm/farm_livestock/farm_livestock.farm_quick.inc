<?php
/**
 * @file
 * Farm livestock quick forms.
 */

/**
 * Implements hook_farm_quick_forms().
 */
function farm_livestock_farm_quick_forms() {
  return array(
    'birth' => array(
      'label' => t('Birth'),
      'permission' => array(
        'create farm_birth log entities',
        'create animal farm_asset entities',
      ),
      'form' => 'farm_livestock_birth_form',
      'file' => 'farm_livestock.farm_quick.birth.inc',
    ),
    'milk' => array(
      'label' => t('Milk'),
      'permission' => 'create farm_harvest log entities',
      'form' => 'farm_livestock_milk_form',
      'file' => 'farm_livestock.farm_quick.milk.inc',
    ),
  );
}
