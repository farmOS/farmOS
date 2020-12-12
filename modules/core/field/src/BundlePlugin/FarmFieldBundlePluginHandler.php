<?php

namespace Drupal\farm_field\BundlePlugin;

use Drupal\entity\BundlePlugin\BundlePluginHandler;

/**
 * Extends BundlePluginHandler to invoke hook_farm_field_bundle_field_info().
 */
class FarmFieldBundlePluginHandler extends BundlePluginHandler {

  /**
   * {@inheritdoc}
   */
  public function getFieldStorageDefinitions() {
    $definitions = [];

    // Allow modules to add definitions.
    foreach (array_keys($this->pluginManager->getDefinitions()) as $plugin_id) {
      $definitions = \Drupal::moduleHandler()->invokeAll('farm_entity_bundle_field_info', [$this->entityType, $plugin_id]);
    }

    // Ensure the presence of required keys which aren't set by the plugin.
    // This is copied directly from the parent method for consistency.
    foreach ($definitions as $field_name => $definition) {
      $definition->setName($field_name);
      $definition->setTargetEntityTypeId($this->entityType->id());
      $definitions[$field_name] = $definition;
    }

    // Get definitions from the parent method.
    $definitions += parent::getFieldStorageDefinitions();

    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldDefinitions($bundle) {

    // Allow modules to add definitions.
    $definitions = \Drupal::moduleHandler()->invokeAll('farm_entity_bundle_field_info', [$this->entityType, $bundle]);

    // Ensure the presence of required keys which aren't set by the plugin.
    // This is copied directly from the parent method for consistency.
    foreach ($definitions as $field_name => $definition) {
      $definition->setName($field_name);
      $definition->setTargetEntityTypeId($this->entityType->id());
      $definition->setTargetBundle($bundle);
      $definitions[$field_name] = $definition;
    }

    // Get definitions from the parent method.
    $definitions += parent::getFieldDefinitions($bundle);

    return $definitions;
  }

}
