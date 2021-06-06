<?php

namespace Drupal\farm_map\Traits;

use Drupal\Component\Utility\Html;
use Drupal\farm_map\Event\MapRenderEvent;


/**
 * A trait for performing common map pre-render steps.
 */
trait PreRenderableMapTrait {

  /**
   * Helper function to perform common map pre-render steps for the map render array.
   *
   * @param array $element
   *   A renderable array containing a #map_type property, which will be
   *   appended to 'farm-map-' as the map element ID.
   *
   * @return array
   *   A renderable array representing the map.
   */
  protected static function preRenderMapCommon(array $element) {

      if (empty($element['#attributes']['id'])) {
          // Set the id to the map name.
          $map_id = Html::getUniqueId('farm-map-' . $element['#map_type']);
          $element['#attributes']['id'] = $map_id;
      } else {
          $map_id = $element['#attributes']['id'];
      }

      // Get the map type.
      /** @var \Drupal\farm_map\Entity\MapTypeInterface $map */
      $map = \Drupal::entityTypeManager()->getStorage('map_type')->load($element['#map_type']);

      // Attach the farmOS-map and farm_map libraries.
      $element['#attached']['library'][] = 'farm_map/farmOS-map';
      $element['#attached']['library'][] = 'farm_map/farm_map';

      // Include map settings.
      $map_settings = !empty($element['#map_settings']) ? $element['#map_settings'] : [];

      // Include the map options.
      $map_options = $map->getMapOptions();

      // Add the instance settings under the map id key.
      $instance_settings = array_merge_recursive($map_settings, $map_options);
      $element['#attached']['drupalSettings']['farm_map'][$map_id] = $instance_settings;

      // Create and dispatch a MapRenderEvent.
      $event = new MapRenderEvent($map, $element);
      \Drupal::service('event_dispatcher')->dispatch(MapRenderEvent::EVENT_NAME, $event);

      // Return the element.
      return $event->element;
  }

}
