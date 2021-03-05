<?php

/**
 * @file
 * Code for creating farmOS group membership base field definitions.
 */

use Drupal\farm_group\Field\AssetGroupItemList;

/**
 * Define asset group base fields.
 */
function farm_group_asset_base_fields() {
  $fields = [];

  // Group membership field.
  // This is computed based on an asset's group assignment logs.
  $options = [
    'type' => 'entity_reference',
    'label' => t('Group membership'),
    'target_type' => 'asset',
    'target_bundle' => 'group',
    'multiple' => TRUE,
    'computed' => AssetGroupItemList::class,
    'hidden' => 'form',
    'weight' => [
      'view' => 94,
    ],
  ];
  $fields['group'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  return $fields;
}

/**
 * Define log group base fields.
 */
function farm_group_log_base_fields() {
  $fields = [];

  // "Is group assignment" boolean field.
  $options = [
    'type' => 'boolean',
    'label' => t('Is group assignment'),
    'description' => t('If this log is a group assignment, any referenced assets will become members of the groups referenced below.'),
    'weight' => [
      'form' => 96,
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
  $fields['is_group_assignment'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  // Group reference field.
  $options = [
    'type' => 'entity_reference',
    'label' => t('Groups'),
    'description' => t('If this is a group assignment log, which groups should the referenced assets be assigned to?'),
    'target_type' => 'asset',
    'target_bundle' => 'group',
    'multiple' => TRUE,
    'weight' => [
      'form' => 97,
      'view' => 97,
    ],
  ];
  $fields['group'] = \Drupal::service('farm_field.factory')->baseFieldDefinition($options);

  return $fields;
}
