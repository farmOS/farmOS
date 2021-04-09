<?php

/**
 * @file
 * Farm_quantity_price module views integration.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_quantity_price_views_data_alter(array &$data) {

  // Create a value field, filter, and sort using fraction decimal handlers.
  $fraction_fields = [
    'numerator' => 'unit_price_numerator',
    'denominator' => 'unit_price_denominator',
  ];
  $data['quantity__unit_price']['unit_price_value'] = [
    'title' => t('Unit price'),
    'help' => t('Value of the unit price, in decimal format.'),
    'real field' => 'unit_price_numerator',
    'field' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
    'filter' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
  ];
}
