<?php

namespace Drupal\farm_location;

/**
 * An object that wraps the GeoPHP Geometry with additional properties.
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
