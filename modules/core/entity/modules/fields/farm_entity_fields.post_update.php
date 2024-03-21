<?php

/**
 * @file
 * Updates farm_entity_fields module.
 */

/**
 * Install farm_parent module.
 */
function farm_entity_fields_post_update_enable_farm_parent(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_parent')) {
    \Drupal::service('module_installer')->install(['farm_parent']);
  }
}

/**
 * Install taxonomy term file and image fields.
 */
function farm_entity_fields_post_update_install_term_file_fields(&$sandbox) {
  $entity_type = 'taxonomy_term';
  $module_name = 'farm_entity_fields';

  // Install file field.
  $field_info = [
    'type' => 'file',
    'label' => t('Files'),
    'file_directory' => 'farm/term/[date:custom:Y]-[date:custom:m]',
    'multiple' => TRUE,
    'weight' => [
      'form' => 90,
      'view' => 90,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->baseFieldDefinition($field_info);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('file', $entity_type, $module_name, $field_definition);

  // Install image field.
  $field_info = [
    'type' => 'image',
    'label' => t('Images'),
    'file_directory' => 'farm/term/[date:custom:Y]-[date:custom:m]',
    'multiple' => TRUE,
    'weight' => [
      'form' => 89,
      'view' => 89,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->baseFieldDefinition($field_info);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('image', $entity_type, $module_name, $field_definition);

  // If the farm_plant_type module is installed, remove old image field config.
  // This module previously provided an image field for plant_type taxonomy
  // terms, so we need to clean up the configuration entities it created.
  if (\Drupal::moduleHandler()->moduleExists('farm_plant_type')) {
    foreach (['field.field.taxonomy_term.plant_type.image', 'field.storage.taxonomy_term.image'] as $config) {
      \Drupal::configFactory()->getEditable($config)->delete();
    }
  }
}

/**
 * Install taxonomy term ontology URI field.
 */
function farm_entity_fields_post_update_add_term_ontology_uri(&$sandbox) {
  $field_info = [
    'type' => 'uri',
    'label' => t('Ontology URI'),
    'description' => t('Link this term to one or more external ontology item URIs.'),
    'multiple' => TRUE,
    'weight' => [
      'form' => 80,
      'view' => 80,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->baseFieldDefinition($field_info);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('ontology_uri', 'taxonomy_term', 'farm_entity_fields', $field_definition);
}
