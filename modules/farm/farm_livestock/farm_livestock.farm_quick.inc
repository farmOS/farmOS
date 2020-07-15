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
        'create animal farm assets',
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
    'animal_move' => array(
      'label' => t('Move Animals'),
      'permission' => array(
        'create farm_activity log entities',
        'create farm_observation log entities',
      ),
      'form' => 'farm_livestock_move_form',
      'file' => 'farm_livestock.farm_quick.move.inc',
    ),
  );
}
