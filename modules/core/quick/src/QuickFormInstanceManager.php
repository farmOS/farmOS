<?php

namespace Drupal\farm_quick;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_quick\Entity\QuickFormInstance;
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

      // Load quick form instance configuration entities for this plugin.
      $entities = $this->entityTypeManager->getStorage('quick_form')->loadByProperties(['plugin' => $plugin['id']]);
      if (!empty($entities)) {
        $instances += $entities;
      }

      // If there are no config entities, create a new (unsaved) config entity
      // with default values from the plugin.
      else {
        $instances[$plugin['id']] = QuickFormInstance::create(['id' => $plugin['id'], 'plugin' => $plugin['id']]);
      }
    }

    return $instances;
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($id) {

    // First attempt to load a quick form instance config entity.
    $entity = $this->entityTypeManager->getStorage('quick_form')->load($id);
    if (!empty($entity)) {
      $entity->getPlugin()->setQuickId($id);
      return $entity;
    }

    // Otherwise, create a new (unsaved) config entity with default values from
    // the plugin.
    return QuickFormInstance::create(['id' => $id, 'plugin' => $id]);
  }

}
