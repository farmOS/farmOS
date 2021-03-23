<?php

namespace Drupal\farm_entity\BundlePlugin;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\BundlePlugin\BundlePluginInstaller as EntityBundlePluginInstaller;

/**
 * Extends the entity BundlePluginInstaller service.
 *
 * Only removes field storage definitions when not in use by another module.
 * This allows field names to be reused across bundles.
 *
 * @see https://www.drupal.org/project/farm/issues/3200219
 */
class BundlePluginInstaller extends EntityBundlePluginInstaller {

  /**
   * {@inheritdoc}
   */
  public function uninstallBundles(EntityTypeInterface $entity_type, array $modules) {
    $bundle_handler = $this->entityTypeManager->getHandler($entity_type->id(), 'bundle_plugin');
    $bundles = array_filter($bundle_handler->getBundleInfo(), function ($bundle_info) use ($modules) {
      return in_array($bundle_info['provider'], $modules, TRUE);
    });

    /**
     * We need to uninstall the field storage definitions in a separate loop.
     *
     * This way we can allow a module to re-use the same field within multiple
     * bundles, allowing e.g to subclass a bundle plugin.
     *
     * @var \Drupal\entity\BundleFieldDefinition[] $field_storage_definitions
     */
    $field_storage_definitions = [];

    // Field definitions that should persist after uninstalling these bundles.
    $field_definitions_to_persist = $this->getFieldDefinitionsToPersist($entity_type, array_keys($bundles));

    foreach (array_keys($bundles) as $bundle) {
      $this->entityBundleListener->onBundleDelete($bundle, $entity_type->id());
      foreach ($bundle_handler->getFieldDefinitions($bundle) as $definition) {
        $field_name = $definition->getName();
        $this->fieldDefinitionListener->onFieldDefinitionDelete($definition);

        // Delete the field storage definition if it should not persist.
        if (!in_array($field_name, array_keys($field_definitions_to_persist))) {
          $field_storage_definitions[$field_name] = $definition;
        }
      }
    }

    foreach ($field_storage_definitions as $definition) {
      $this->fieldStorageDefinitionListener->onFieldStorageDefinitionDelete($definition);
    }
  }

  /**
   * Get field definitions from all remaining bundles.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to check.
   * @param array $uninstalled_bundles
   *   The bundles that will be uninstalled.
   *
   * @return array
   *   Remaining field definitions.
   */
  protected function getFieldDefinitionsToPersist(EntityTypeInterface $entity_type, array $uninstalled_bundles) {
    $bundle_handler = $this->entityTypeManager->getHandler($entity_type->id(), 'bundle_plugin');
    $remaining_bundles = array_filter($bundle_handler->getBundleInfo(), function ($bundle_name) use ($uninstalled_bundles) {
      return !in_array($bundle_name, $uninstalled_bundles, TRUE);
    }, ARRAY_FILTER_USE_KEY);

    $fields_to_persist = [];
    foreach (array_keys($remaining_bundles) as $bundle) {
      foreach ($bundle_handler->getFieldDefinitions($bundle) as $definition) {
        $field_name = $definition->getName();
        if (!isset($fields_to_persist[$field_name])) {
          $fields_to_persist[$field_name] = $definition;
        }
      }
    }

    return $fields_to_persist;
  }

}
