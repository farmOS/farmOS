<?php

namespace Drupal\farm_map\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\farm_map\Traits\PreRenderableMapTrait;

/**
 * Provides a farm_map_prototype render element - that is
 * an element that will be used from Javascript as a prototype
 * of the element upon which to instantiate map instances.
 *
 * @RenderElement("farm_map_prototype")
 */
class FarmMapPrototype extends RenderElement {

  use PreRenderableMapTrait;

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
    ];
  }

  /**
   * Pre-render callback for the map prototype render array.
   *
   * @param array $element
   *   A renderable array containing a #map_type property, which will be
   *   appended to 'farm-map-' as the map element ID.
   *
   * @return array
   *   A renderable array representing the map prototype.
   */
  public static function preRenderMap(array $element) {

    // Add the farm-map class.
    $element['#attributes']['class'][] = 'farm-map-prototype';

    // Return the element.
    return FarmMapPrototype::preRenderMapCommon($element);
  }

}
