<?php

namespace Drupal\farm_geo;

/**
 * An object that wraps the GeoPHP Geometry with additional properties.
 *
 * As suggested by the GeoPHP maintainer:
 *
 * @see https://github.com/phayes/geoPHP/issues/25#issuecomment-5576661
 * @see https://github.com/phayes/geoPHP/pull/41#issuecomment-6983505
 */
class GeometryWrapper {

  /**
   * The geometry to wrap.
   *
   * @var \Geometry
   *   The GeoPHP Geometry object.
   */
  public \Geometry $geometry;

  /**
   * Properties associated with the geometry.
   *
   * @var array
   *   Associative array of property values.
   */
  public array $properties;

  /**
   * GeometryWrapper constructor.
   *
   * @param \Geometry $geometry
   *   The GeoPHP geometry object.
   * @param array $properties
   *   Associative array of property values.
   */
  public function __construct(\Geometry $geometry, array $properties = []) {
    $this->geometry = $geometry;
    $this->properties = $properties;
  }

}
