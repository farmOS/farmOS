<?php

/**
 * @file
 * Hooks provided by farm_entity.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Allows modules to add field definitions to asset, log, and plan bundles.
 *
 * @todo https://www.drupal.org/project/farm/issues/3194206
 *
 * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
 *   The entity type object.
 * @param string $bundle
 *   The machine name of the bundle.
 *
 * @return \Drupal\entity\BundleFieldDefinition[]
 *   Returns an array of BundleFieldDefinition objects.
 */
function hook_farm_entity_bundle_field_info(\Drupal\Core\Entity\EntityTypeInterface $entity_type, string $bundle) {
  $fields = [];

  // Add a new string field to Input Logs.
  if ($entity_type->id() == 'log' && $bundle == 'input') {
    $options = [
      'type' => 'string',
      'label' => t('My new field'),
      'description' => t('My field description.'),
      'weight' => [
        'form' => 10,
        'view' => 10,
      ],
    ];
    $fields['myfield'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  }

  return $fields;
}

/**
 * @} End of "addtogroup hooks".
 */
