<?php

/**
 * @file
 * Post update hooks for the farm_entity module.
 */

/**
 * Enforce entity reference integrity on plan reference fields.
 */
function farm_entity_post_update_enforce_plan_eri(&$sandbox) {
  $config = \Drupal::configFactory()->getEditable('entity_reference_integrity_enforce.settings');
  $entity_types = $config->get('enabled_entity_type_ids');
  $entity_types['plan'] = 'plan';
  $config->set('enabled_entity_type_ids', $entity_types);
  $config->save();
}

/**
 * Rebuild bundle field maps.
 */
function farm_entity_post_update_rebuild_bundle_field_maps(&$sandbox = NULL) {
  \Drupal::service('entity_field.manager')->rebuildBundleFieldMap();
}
