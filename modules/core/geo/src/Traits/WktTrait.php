<?php

namespace Drupal\farm_geo\Traits;

/**
 * Provides methods to work with WKT.
 */
trait WktTrait {

  /**
   * Reduce a WKT geometry.
   *
   * @param string $wkt
   *   The geometry in WKT format.
   *
   * @return string
   *   The reduced geometry in WKT format.
   */
  public function reduceWkt(string $wkt) {
    $geometry = \geoPHP::load($wkt, 'wkt');
    $geometry = \geoPHP::geometryReduce($geometry);
    return $geometry->out('wkt');
  }

  /**
   * Combine WKT geometries.
   *
   * This does not use Geometry::union(), which is only available when GEOS is
   * installed.
   *
   * @param array $geoms
   *   An array of WKT geometry strings.
   *
   * @return string
   *   Returns a combined WKT geometry string.
   */
  public function combineWkt(array $geoms) {

    // If no geometries were found, return an empty string.
    if (empty($geoms)) {
      return '';
    }

    // If there is more than one geometry, we will wrap it all in a
    // GEOMETRYCOLLECTION() at the end.
    $geometrycollection = FALSE;
    if (count($geoms) > 1) {
      $geometrycollection = TRUE;
    }

    // Build an array of WKT strings.
    $wkt_strings = [];
    foreach ($geoms as $geom) {

      // If the geometry is empty, skip it.
      if (empty($geom)) {
        continue;
      }

      // Convert to a GeoPHP geometry object.
      $geometry = \geoPHP::load($geom, 'wkt');

      // If this is a geometry collection, multi-point, multi-linestring, or
      // multi-polygon, then extract its components and add them individually to
      // the array.
      $multigeometries = [
        'GeometryCollection',
        'MultiPoint',
        'MultiLineSting',
        'MultiPolygon',
      ];
      if (in_array($geometry->geometryType(), $multigeometries)) {

        // Iterate through the geometry components and add each to the array.
        $components = $geometry->getComponents();
        foreach ($components as $component) {
          $wkt_strings[] = $component->out('wkt');
        }

        // Set $geometrycollection to TRUE in case there was only one geometry
        // in the $geoms parameter of this function, so that we know to wrap the
        // WKT in a GEOMETRYCOLLECTION() at the end.
        $geometrycollection = TRUE;
      }

      // Otherwise, add it to the array.
      else {
        $wkt_strings[] = $geometry->out('wkt');
      }
    }

    // Combine all the WKT strings together into one.
    $wkt = implode(',', $wkt_strings);

    // If the WKT is empty, return it.
    if (empty($wkt)) {
      return $wkt;
    }

    // If there is more than one geometry, wrap them all in a geometry
    // collection.
    if ($geometrycollection) {
      $wkt = 'GEOMETRYCOLLECTION (' . $wkt . ')';
    }

    // Return the combined WKT.
    return $wkt;
  }

}
