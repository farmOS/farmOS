<?php

namespace Drupal\farm_map\Element;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Url;
use Drupal\farm_map\Event\MapRenderEvent;

/**
 * Provides a farm_map render element.
 *
 * @RenderElement("farm_map")
 */
class FarmMap extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRenderMap'],
      ],
      '#theme' => 'farm_map',
      '#map_type' => 'default',
      '#map_settings' => [],
      '#behaviors' => [],
    ];
  }

  /**
   * Pre-render callback for the map render array.
   *
   * @param array $element
   *   A renderable array containing a #map_type property, which will be
   *   appended to 'farm-map-' as the map element ID if one has not already
   *   been provided.
   *
   * @return array
   *   A renderable array representing the map.
   *
   * @see \Drupal\farm_map\Event\MapRenderEvent
   */
  public static function preRenderMap(array $element) {

    if (empty($element['#attributes']['id'])) {
      // Set the id to the map name.
      $map_id = Html::getUniqueId('farm-map-' . $element['#map_type']);
      $element['#attributes']['id'] = $map_id;
    }

    else {
      $map_id = $element['#attributes']['id'];
    }

    // Get the entity type manager.
    $entity_type_manager = \Drupal::entityTypeManager();

    // Get the map type.
    /** @var \Drupal\farm_map\Entity\MapTypeInterface $map */
    $map = $entity_type_manager->getStorage('map_type')->load($element['#map_type']);

    // Add the farm-map class.
    $element['#attributes']['class'][] = 'farm-map';

    // By default, inform farm_map.js that it should instantiate the map.
    if (empty($element['#attributes']['data-map-instantiator'])) {
      $element['#attributes']['data-map-instantiator'] = 'farm_map';
    }

    // Attach the farmOS-map and farm_map libraries.
    $element['#attached']['library'][] = 'farm_map/farmOS-map';
    $element['#attached']['library'][] = 'farm_map/farm_map';

    // Determine the public path for the farmOS-map library.
    // Get the farm_map/farmOS-map library.
    /** @var \Drupal\Core\Asset\LibraryDiscovery $library_discovery */
    $library_discovery = \Drupal::service('library.discovery');
    $library_info = $library_discovery->getLibraryByName('farm_map', 'farmOS-map');

    // Build an absolute server path to the farmOS-map library that includes the
    // Drupal base path.
    $js_path = $library_info['js'][0]['data'];
    $absolute_js_path = Url::fromUri("base:$js_path")->setAbsolute(FALSE)->toString();

    // Remove 13 characters of farmOS-map.js from the path.
    $public_path = substr($absolute_js_path, 0, -13);

    // Add public base path as settings for farm_map_public_path.
    $element['#attached']['drupalSettings']['farm_map_public_path'] = $public_path;

    // If #behaviors are included, attach each one.
    foreach ($element['#behaviors'] as $behavior_name) {
      /** @var \Drupal\farm_map\Entity\MapBehaviorInterface $behavior */
      $behavior = $entity_type_manager->getStorage('map_behavior')->load($behavior_name);
      if (!is_null($behavior)) {
        $element['#attached']['library'][] = $behavior->getLibrary();
      }
    }

    // Include the map options.
    $map_options = $map->getMapOptions();

    // Add the instance settings under the map id key.
    $instance_settings = array_merge_recursive($element['#map_settings'], $map_options);
    $element['#attached']['drupalSettings']['farm_map'][$map_id] = $instance_settings;

    // Create and dispatch a MapRenderEvent.
    $event = new MapRenderEvent($map, $element, $entity_type_manager);
    \Drupal::service('event_dispatcher')->dispatch($event, MapRenderEvent::EVENT_NAME);

    // Return the element.
    return $event->element;
  }

}
