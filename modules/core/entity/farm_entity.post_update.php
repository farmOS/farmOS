<?php

/**
 * @file
 * Post update functions for farmOS entity module.
 */

/**
 * Add hook_farm_entity_bundle_field_info() fields to bundle field maps.
 */
function farm_entity_post_update_add_farm_entity_bundle_field_maps(&$sandbox = NULL) {

  // Iterate through entity types.
  $entity_type_definitions = \Drupal::service('entity_type.manager')->getDefinitions();
  foreach ($entity_type_definitions as $entity_type => $entity_type_definition) {

    // Only proceed for entity types that use bundle plugins.
    if (!in_array($entity_type, ['asset', 'log', 'plan', 'quantity'])) {
      continue;
    }

    // Get the bundle field map key value collection.
    $bundle_field_map = \Drupal::service('keyvalue')->get('entity.definitions.bundle_field_map')->get($entity_type) ?? [];

    // Get a list of installed bundles for this entity type.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($entity_type);

    // Iterate through bundles.
    foreach ($bundles as $bundle => $bundle_info) {

      // Invoke hook_farm_entity_bundle_field_info() on all modules with a
      // callback function to add bundle fields to the entity field map.
      \Drupal::service('module_handler')->invokeAllWith(
        'farm_entity_bundle_field_info',
        function (callable $hook) use ($entity_type_definition, $bundle, &$bundle_field_map) {

          // Get bundle fields defined by the module.
          $bundle_fields = $hook($entity_type_definition, $bundle) ?? [];

          // If bundle fields are empty, bail.
          if (empty($bundle_fields)) {
            return;
          }

          // Iterate through the bundle field definitions to add fields to the
          // bundle field map. This mimics the field_definition.listener
          // service's onFieldDefinitionCreate() behavior.
          // @see Drupal\Core\Field\FieldDefinitionListener::onFieldDefinitionCreate()
          foreach ($bundle_fields as $field_name => $bundle_field) {
            if (!isset($bundle_field_map[$field_name])) {
              // This field did not exist yet, initialize it with the type and
              // empty bundle list.
              $bundle_field_map[$field_name] = [
                'type' => $bundle_field->getType(),
                'bundles' => [],
              ];
            }
            $bundle_field_map[$field_name]['bundles'][$bundle] = $bundle;
          }
        }
      );
    }

    // Set the bundle field map key value collection.
    if (!empty($bundle_field_map)) {
      \Drupal::service('keyvalue')->get('entity.definitions.bundle_field_map')->set($entity_type, $bundle_field_map);
    }
  }

  // Delete the entity field map cache entry.
  \Drupal::service('cache.discovery')->delete('entity_field_map');
}
