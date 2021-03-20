<?php

/**
 * @file
 * Code for creating inventory base field definitions.
 */

use Drupal\farm_inventory\Field\AssetInventoryItemList;

/**
 * Define asset base fields for inventory tracking.
 */
function farm_inventory_asset_base_fields() {
  $field_info = [
    'inventory' => [
      'type' => 'inventory',
      'label' => t('Current inventory'),
      'multiple' => TRUE,
      'computed' => AssetInventoryItemList::class,
      'hidden' => 'form',
      'weight' => [
        'view' => 94,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }
  return $fields;
}

/**
 * Define quantity base fields for inventory tracking.
 */
function farm_inventory_quantity_base_fields() {
  $field_info = [
    'inventory_adjustment' => [
      'type' => 'list_string',
      'label' => t('Inventory adjustment'),
      'description' => t('What type of inventory adjustment is this?'),
      'allowed_values' => [
        'increment' => t('Increment'),
        'decrement' => t('Decrement'),
        'reset' => t('Reset'),
      ],
      'multiple' => FALSE,
      'weight' => [
        'form' => 50,
        'view' => 50,
      ],
    ],
    'inventory_asset' => [
      'type' => 'entity_reference',
      'label' => t('Inventory asset'),
      'description' => t('Which asset will this adjust the inventory of?'),
      'target_type' => 'asset',
      'multiple' => FALSE,
      'weight' => [
        'form' => 51,
        'view' => 51,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }
  return $fields;
}
