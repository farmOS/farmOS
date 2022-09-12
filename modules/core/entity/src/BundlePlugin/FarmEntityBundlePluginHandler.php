<?php

namespace Drupal\farm_entity\BundlePlugin;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\entity\BundlePlugin\BundlePluginHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extends BundlePluginHandler to invoke hook_farm_entity_bundle_field_info().
 *
 * @todo https://www.drupal.org/project/farm/issues/3194206
 */
class FarmEntityBundlePluginHandler extends BundlePluginHandler {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new FarmEntityBundlePluginHandler object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   The bundle plugin manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(EntityTypeInterface $entity_type, PluginManagerInterface $plugin_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($entity_type, $plugin_manager);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('plugin.manager.' . $entity_type->get('bundle_plugin_type')),
      $container->get('module_handler'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldStorageDefinitions() {
    $definitions = [];

    // Allow modules to add definitions.
    foreach (array_keys($this->pluginManager->getDefinitions()) as $plugin_id) {
      $definitions += $this->moduleHandler->invokeAll('farm_entity_bundle_field_info', [$this->entityType, $plugin_id]);
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
    $definitions = $this->moduleHandler->invokeAll('farm_entity_bundle_field_info', [$this->entityType, $bundle]);

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
