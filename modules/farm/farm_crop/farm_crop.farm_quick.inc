<?php
/**
 * @file
 * Farm crop quick forms.
 */

/**
 * Implements hook_farm_quick_forms().
 */
function farm_crop_farm_quick_forms() {
  return array(
    'planting' => array(
      'label' => t('Planting'),
      'permission' => 'create planting farm assets',
      'form' => 'farm_crop_planting_quick_form',
      'file' => 'farm_crop.farm_quick.planting.inc',
    ),
  );
}
