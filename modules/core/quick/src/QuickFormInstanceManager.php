<?php

namespace Drupal\farm_quick;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Quick form instance manager.
 */
class QuickFormInstanceManager implements QuickFormInstanceManagerInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The quick form plugin manager.
   *
   * @var \Drupal\farm_quick\QuickFormPluginManager
   */
  protected $quickFormPluginManager;

  /**
   * Constructs a QuickFormInstanceManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\farm_quick\QuickFormPluginManager $quick_form_plugin_manager
   *   The quick form plugin manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, QuickFormPluginManager $quick_form_plugin_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->quickFormPluginManager = $quick_form_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.quick_form'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getInstances(): array {
    $instances = [];

    // Iterate through quick form plugin definitions.
    foreach ($this->quickFormPluginManager->getDefinitions() as $plugin) {

      // Instantiate a quick form for the plugin.
      $instances[$plugin['id']] = $this->quickFormPluginManager->createInstance($plugin['id']);
    }

    return $instances;
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($id) {
    return $this->quickFormPluginManager->createInstance($id);
  }

}
