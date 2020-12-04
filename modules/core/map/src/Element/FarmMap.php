<?php

namespace Drupal\farm_map\Element;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Element\RenderElement;

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
    $default_name = 'default';
    return [
      '#pre_render' => [
        [$class, 'preRenderMap'],
      ],
      '#theme' => 'farm_map',
      '#map_name' => $default_name,
    ];
  }

  /**
   * Pre-render callback for the map render array.
   *
   * @param array $element
   *   A renderable array containing a #map_name property, which will be used
   *   as the map div ID.
   *
   * @return array
   *   A renderable array representing the map.
   */
  public static function preRenderMap(array $element) {

    // Set the id to the map name.
    $element['#attributes']['id'] = Html::getUniqueId($element['#map_name']);

    // Add the farm-map class.
    $element['#attributes']['class'][] = 'farm-map';

    // Attach the farmOS-map and farm_map libraries.
    $element['#attached']['library'][] = 'farm_map/farmOS-map';
    $element['#attached']['library'][] = 'farm_map/farm_map';
    return $element;
  }

}
