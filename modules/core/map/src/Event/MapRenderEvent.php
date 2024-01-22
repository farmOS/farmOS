<?php

namespace Drupal\farm_map\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_map\Entity\MapTypeInterface;

/**
 * An event that is dispatched before rendering a map on the page.
 *
 * @group farm
 */
class MapRenderEvent extends Event {

  const EVENT_NAME = 'map_render_event';

  /**
   * The farm_map render element.
   *
   * @var \Drupal\farm_map\Element\FarmMap
   */
  public $element;

  /**
   * The map type config entity.
   *
   * @var \Drupal\farm_map\Entity\MapTypeInterface
   */
  private $mapType;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * MapRenderEvent constructor.
   *
   * @param \Drupal\farm_map\Entity\MapTypeInterface $map_type
   *   The farm_map render element.
   * @param array $element
   *   The farm_map render array.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(MapTypeInterface $map_type, array $element, EntityTypeManagerInterface $entity_type_manager) {
    $this->element = $element;
    $this->mapType = $map_type;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Getter method to get the map target ID.
   *
   * @return string
   *   The map target ID.
   */
  public function getMapTargetId() {
    return $this->element['#attributes']['id'];
  }

  /**
   * Getter method to get the map type being rendered.
   *
   * @return \Drupal\farm_map\Entity\MapTypeInterface
   *   The map type config entity.
   */
  public function getMapType() {
    return $this->mapType;
  }

  /**
   * Getter method for map behaviors.
   *
   * This returns a merged list of map behaviors from both the map type
   * configuration and the map element's #behaviors property.
   *
   * @return string[]
   *   An array of map behavior IDs.
   */
  public function getMapBehaviors() {
    $behaviors = $this->getMapType()->getMapBehaviors();
    if (!empty($this->element['#behaviors'])) {
      $behaviors = array_merge($behaviors, $this->element['#behaviors']);
    }
    return $behaviors;
  }

  /**
   * Add behavior to the map.
   *
   * @param string $behavior_name
   *   The behavior name.
   * @param array $settings
   *   Optional behavior settings that will be added to
   *   drupalSettings.farm_map.behaviors.behavior_name.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @see \Drupal\farm_map\Element\FarmMap
   */
  public function addBehavior(string $behavior_name, array $settings = []) {

    // Load the behavior.
    /** @var \Drupal\farm_map\Entity\MapBehaviorInterface $behavior */
    $behavior = $this->entityTypeManager->getStorage('map_behavior')->load($behavior_name);

    // If the behavior has a library, attach it.
    if (!empty($behavior->getLibrary())) {
      $this->element['#attached']['library'][] = $behavior->getLibrary();
    }

    // Add behavior settings if supplied.
    if (!empty($settings)) {
      $behaviorSettings['behaviors'][$behavior_name] = $settings;
      $this->addSettings($behaviorSettings);
    }
  }

  /**
   * Add settings to the map.
   *
   * These settings will be added to drupalSettings.farm_map.
   *
   * @param array $settings
   *   The settings to be added.
   */
  public function addSettings(array $settings) {
    $existing = [];
    if (!empty($this->element['#attached']['drupalSettings']['farm_map'])) {
      $existing = $this->element['#attached']['drupalSettings']['farm_map'];
    }
    $this->element['#attached']['drupalSettings']['farm_map'] = array_replace_recursive($existing, $settings);
  }

  /**
   * Add cache tags to the render element.
   *
   * @param array $tags
   *   An array of cache tags.
   */
  public function addCacheTags(array $tags) {
    $existing = [];
    if (!empty($this->element['#cache']['tags'])) {
      $existing = $this->element['#cache']['tags'];
    }
    $this->element['#cache']['tags'] = array_unique(array_merge($tags, $existing));
  }

}
