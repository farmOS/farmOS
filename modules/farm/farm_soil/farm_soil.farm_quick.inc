<?php
/**
 * @file
 * Farm soil quick forms.
 */

/**
 * Implements hook_farm_quick_forms().
 */
function farm_soil_farm_quick_forms() {
  return array(
    'amendment' => array(
      'label' => t('Soil amendment'),
      'permission' => 'create farm_input log entities',
      'form' => 'farm_soil_amendment_form',
      'file' => 'farm_soil.farm_quick.amendment.inc',
    ),
  );
}
