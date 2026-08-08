<?php

/**
 * @file
 * Post update hooks for the farm_entity module.
 */

declare(strict_types=1);

/**
 * Enforce entity reference integrity on organization reference fields.
 */
function farm_entity_post_update_enforce_organization_eri(&$sandbox) {
  $config = \Drupal::configFactory()->getEditable('entity_reference_integrity_enforce.settings');
  $entity_types = $config->get('enabled_entity_type_ids');
  $entity_types['organization'] = 'organization';
  $config->set('enabled_entity_type_ids', $entity_types);
  $config->save();
}

/**
 * Consider all existing revision translations affected.
 */
function farm_entity_post_update_revision_translations_affected(&$sandbox) {
  $entity_types = [
    'asset',
    'log',
    'organization',
    'plan',
  ];
  foreach ($entity_types as $entity_type) {
    if (\Drupal::moduleHandler()->moduleExists($entity_type)) {
      \Drupal::database()->query('UPDATE {' . $entity_type . '_field_data} SET revision_translation_affected = 1');
      \Drupal::database()->query('UPDATE {' . $entity_type . '_field_revision} SET revision_translation_affected = 1');
    }
  }
}

/**
 * Install the diff module.
 */
function farm_entity_post_update_install_diff(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('diff')) {
    \Drupal::service('module_installer')->install(['diff']);
  }
}

/**
 * Implements hook_removed_post_updates().
 */
function farm_entity_removed_post_updates() {
  return [
    'farm_entity_post_update_enforce_plan_eri' => '4.x',
    'farm_entity_post_update_rebuild_bundle_field_maps' => '4.x',
    'farm_entity_post_update_uninstall_exif_orientation' => '4.x',
  ];
}
