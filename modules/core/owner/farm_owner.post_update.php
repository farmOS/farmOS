<?php

/**
 * @file
 * Updates farm_owner module.
 */

use Drupal\system\Entity\Action;

/**
 * Add 'owner' field to assets.
 */
function farm_owner_post_update_add_asset_owner(&$sandbox = NULL) {
  $entity_type = 'asset';
  $module_name = 'farm_owner';
  $field_name = 'owner';

  $field_info = [
    'type' => 'entity_reference',
    'label' => t('Assigned to'),
    'description' => t('Optionally assign this task to one or more people.'),
    'target_type' => 'user',
    'multiple' => TRUE,
    'weight' => [
      'form' => -70,
      'view' => -70,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->baseFieldDefinition($field_info);
  \Drupal::entityDefinitionUpdateManager()
    ->installFieldStorageDefinition($field_name, $entity_type, $module_name, $field_definition);

  // Create action for assigning assets to users.
  $action = Action::create([
    'id' => 'asset_assign_action',
    'label' => t('Assign asset'),
    'type' => 'asset',
    'plugin' => 'asset_assign_action',
    'configuration' => [],
  ]);
  $action->save();
}
