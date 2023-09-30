<?php

/**
 * @file
 * Code for creating farmOS entity location base field definitions.
 */

use Drupal\farm_location\Field\AssetGeometryItemList;
use Drupal\farm_location\Field\AssetLocationItemList;

/**
 * Define asset location base fields.
 */
function farm_location_asset_base_fields() {
  $fields = [];

  // Current location field.
  // This is computed based on an asset's movements.
  $options = [
    'type' => 'entity_reference',
    'label' => t('Current location'),
    'target_type' => 'asset',
    'multiple' => TRUE,
    'computed' => AssetLocationItemList::class,
    'hidden' => 'form',
    'view_display_options' => [
      'label' => 'inline',
      'type' => 'asset_current_location',
      'settings' => [
        'link' => TRUE,
        'render_without_location' => TRUE,
      ],
      'weight' => 95,
    ],
  ];
  $fields['location'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  // Current geometry field.
  // This is computed based on an asset's movements or its intrinsic geometry.
  $options = [
    'type' => 'geofield',
    'label' => t('Current geometry'),
    'computed' => AssetGeometryItemList::class,
    'hidden' => 'form',
    'weight' => [
      'view' => 95,
    ],
  ];
  $fields['geometry'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  // Intrinsic geometry field.
  // This is added as a bundle field definition to all asset types rather than
  // a base field definition so that data is stored in a dedicated database
  // table.
  $options = [
    'type' => 'geofield',
    'label' => t('Intrinsic geometry'),
    'description' => t('Add geometry data to this asset to describe its intrinsic location. This will only be used if the asset is fixed.'),
    'weight' => [
      'form' => 96,
    ],
    'hidden' => 'view',
    'populate_file_field' => 'file',
  ];
  $fields['intrinsic_geometry'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

  // Location boolean field.
  $options = [
    'type' => 'boolean',
    'label' => t('Is location'),
    'description' => t('If this asset is a location, then other assets can be moved to it.'),
    'default_value_callback' => 'farm_location_is_location_default_value',
    'weight' => [
      'form' => 95,
    ],
    'view_display_options' => [
      'label' => 'inline',
      'type' => 'hideable_boolean',
      'settings' => [
        'format' => 'default',
        'format_custom_false' => '',
        'format_custom_true' => '',
        'hide_if_false' => TRUE,
      ],
      'weight' => 95,
    ],
  ];
  $fields['is_location'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  // Fixed boolean field.
  $options = [
    'type' => 'boolean',
    'label' => t('Is fixed'),
    'description' => t('If this asset is fixed, then it can have an intrinsic geometry. If the asset will move around, then it is not fixed and geometry will be determined by movement logs.'),
    'default_value_callback' => 'farm_location_is_fixed_default_value',
    'weight' => [
      'form' => 95,
    ],
    'view_display_options' => [
      'label' => 'inline',
      'type' => 'hideable_boolean',
      'settings' => [
        'format' => 'default',
        'format_custom_false' => '',
        'format_custom_true' => '',
        'hide_if_false' => TRUE,
      ],
      'weight' => 96,
    ],
  ];
  $fields['is_fixed'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  return $fields;
}

/**
 * Define log location base fields.
 */
function farm_location_log_base_fields() {
  $fields = [];

  // Location asset reference field.
  $options = [
    'type' => 'entity_reference',
    'label' => t('Location'),
    'description' => t('Where does this take place?'),
    'target_type' => 'asset',
    'multiple' => TRUE,
    'weight' => [
      'form' => 90,
      'view' => 90,
    ],
  ];
  $field = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);
  $field->setSetting('handler', 'views');
  $field->setSetting('handler_settings', [
    'view' => [
      'view_name' => 'farm_location_reference',
      'display_name' => 'entity_reference',
    ],
  ]);
  $fields['location'] = $field;

  // Geometry field.
  // This is added as a bundle field definition to all log types rather than
  // a base field definition so that data is stored in a dedicated database
  // table.
  $options = [
    'type' => 'geofield',
    'label' => t('Geometry'),
    'description' => t('Add geometry data to this log to describe where it took place.'),
    'weight' => [
      'form' => 95,
      'view' => 95,
    ],
    'populate_file_field' => 'file',
  ];
  $fields['geometry'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

  // Movement boolean field.
  $options = [
    'type' => 'boolean',
    'label' => t('Is movement'),
    'description' => t('If this log is a movement, then all assets referenced by it will be located in the referenced locations and/or geometry at the time the log takes place. The log must be complete in order for the movement to take effect.'),
    'default_value_callback' => 'farm_location_is_movement_default_value',
    'weight' => [
      'form' => 95,
    ],
    'view_display_options' => [
      'label' => 'inline',
      'type' => 'hideable_boolean',
      'settings' => [
        'format' => 'default',
        'format_custom_false' => '',
        'format_custom_true' => '',
        'hide_if_false' => TRUE,
      ],
      'weight' => 95,
    ],
  ];
  $fields['is_movement'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  return $fields;
}
