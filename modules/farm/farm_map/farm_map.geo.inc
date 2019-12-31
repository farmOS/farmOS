<?php

/**
 * @file
 * Farm map geometry functions.
 */

/**
 * Helper function for setting BCMath scale. We use this (along with
 * farm_map_reset_bcscale()) in geometry functions below to consistently call
 * bcscale() with a high precision, so that small latitude/longitude geometries
 * can have their length/area calculated with maximum precision.
 *
 * @see https://github.com/phayes/geoPHP/issues/114#issuecomment-160463523
 */
function farm_map_set_bcscale($reset = FALSE) {

  // The first time this function is called, we will save the current bcscale()
  // value in a static variable, so that it can be reset in the future.
  static $scale;

  // Reset the scale, if desired.
  if (!empty($reset)) {

    // If the scale has not been set, bail.
    // This prevents farm_map_reset_bcscale() being called before this function.
    if (!isset($scale)) {
      return;
    }

    // Otherwise, reset.
    bcscale($scale);
    return;
  }

  // Set the scale, if it has not been already.
  if (!isset($scale)) {
    $scale = bcscale(24);
  }
}

/**
 * Helper function for resetting BCMath scale.
 */
function farm_map_reset_bcscale() {
  farm_map_set_bcscale(TRUE);
}

/**
 * Combine multiple WKT geometries into a single GeoPHP geometry object.
 *
 * @param array $geoms
 *   An array of geometry strings in WKT format.
 *
 * @return object|bool
 *   Returns a GeoPHP object, or FALSE on failure.
 */
function farm_map_combine_geoms($geoms = array()) {

  // If no geometries were found, return an empty geometry.
  if (empty($geoms)) {
    return FALSE;
  }

  // Load the GeoPHP library.
  geophp_load();

  // If there is more than one geometry, we will wrap it all in a
  // GEOMETRYCOLLECTION() at the end.
  $geometrycollection = FALSE;
  if (count($geoms) > 1) {
    $geometrycollection = TRUE;
  }

  // Build an array of WKT strings.
  $wkt_strings = array();
  foreach ($geoms as &$geom) {

    // Convert to a GeoPHP geometry object.
    $geometry = geoPHP::load($geom, 'wkt');

    // If this is a geometry collection, multi-point, multi-linestring, or
    // multi-polygon, then extract its components and add them individually to
    // the array.
    $multigeometries = array(
      'GeometryCollection',
      'MultiPoint',
      'MultiLineSting',
      'MultiPolygon',
    );
    if (in_array($geometry->geometryType(), $multigeometries)) {

      // Iterate through the geometry components and add each to the array.
      $components = $geometry->getComponents();
      foreach ($components as $component) {
        $wkt_strings[] = $component->asText();
      }

      // Set $geometrycollection to TRUE in case there was only one geometry in
      // the $geoms parameter of this function, so that we know to wrap the WKT
      // in a GEOMETRYCOLLECTION() at the end.
      $geometrycollection = TRUE;
    }

    // Otherwise, add it to the array.
    else {
      $wkt_strings[] = $geometry->asText();
    }
  }

  // Combine all the WKT strings together into one.
  $wkt = implode(',', $wkt_strings);

  // If there is more than one geometry, wrap them all in a geometry collection.
  if ($geometrycollection) {
    $wkt = 'GEOMETRYCOLLECTION (' . $wkt . ')';
  }

  // Convert to a final GeoPHP geometry object and reduce the geometry.
  $geometry = geoPHP::load($wkt, 'wkt');
  $geometry = geoPHP::geometryReduce($geometry);

  // Return the geometry.
  return $geometry;
}

/**
 * Calculate latitude degree length at a given latitude. Equations are taken
 * from https://en.wikipedia.org/wiki/Geographic_coordinate_system#Expressing_latitude_and_longitude_as_linear_units
 *
 * @param $lat
 *   The latitude to calculate degree length at, in degrees.
 *
 * @return string
 *   Returns the length of a degree of latitude at the given latitude as a
 *   string, in meters.
 */
function farm_map_lat_deg_len($lat) {

  // Load GeoPHP.
  geophp_load();

  // Set BCMath scale.
  farm_map_set_bcscale();

  // Convert degrees to radians.
  $lat = deg2rad($lat);

  // Define coefficients. These are copied from
  // http://gis.stackexchange.com/questions/75528/length-of-a-degree-where-do-the-terms-in-this-formula-come-from
  $m1 = 111132.95255;
  $m2 = 559.84957;
  $m3 = 1.17514;
  $m4 = 0.00230;

  // If BCMath is available, use that. Otherwise, use normal PHP float
  // operations.
  if (geoPHP::bcmathInstalled()) {
    $length = bcsub($m1, bcadd(bcmul($m2, cos(bcmul(2, $lat))), bcsub(bcmul($m3, cos(bcmul(4, $lat))), bcmul($m4, cos(bcmul(6, $lat))))));
  }
  else {
    $length = $m1 - ($m2 * cos(2 * $lat)) + ($m3 * cos(4 * $lat)) - ($m4 * cos(6 * $lat));
  }

  // Reset BCMath scale.
  farm_map_reset_bcscale();

  // Return the length.
  return (string) $length;
}

/**
 * Calculate longitude degree length at a given latitude. Equations are taken
 * from https://en.wikipedia.org/wiki/Geographic_coordinate_system#Expressing_latitude_and_longitude_as_linear_units
 * See also http://gis.stackexchange.com/questions/75528/length-of-a-degree-where-do-the-terms-in-this-formula-come-from
 *
 * @param $lat
 *   The latitude to calculate degree length at, in degrees.
 *
 * @return string
 *   Returns the length of a degree of longitude at the given latitude as a
 *   string, in meters.
 */
function farm_map_lon_deg_len($lat) {

  // Load GeoPHP.
  geophp_load();

  // Set BCMath scale.
  farm_map_set_bcscale();

  // Convert degrees to radians.
  $lat = deg2rad($lat);

  // Define coefficients. These are copied from
  // http://gis.stackexchange.com/questions/75528/length-of-a-degree-where-do-the-terms-in-this-formula-come-from
  $p1 = 111412.87733;
  $p2 = 93.50412;
  $p3 = 0.11774;

  // If BCMath is available, use that. Otherwise, use normal PHP float
  // operations.
  if (geoPHP::bcmathInstalled()) {
    $length = bcsub(bcmul($p1, cos($lat)), bcsub(bcmul($p2, cos(bcmul(3, $lat))), bcmul($p3, cos(bcmul(5, $lat)))));
  }
  else {
    $length = ($p1 * cos($lat)) - ($p2 * cos(3 * $lat)) - ($p3 * cos(5 * $lat));
  }

  // Reset BCMath scale.
  farm_map_reset_bcscale();

  // Return the length.
  return (string) $length;
}

/**
 * Calculate the distance between two latitude/longitude points in meters.
 *
 * @param Point $p1
 *   The first point.
 * @param Point $p2
 *   The second point.
 *
 * @return string
 *   Returns the distance as a string, in meters.
 */
function farm_map_distance($p1, $p2) {

  // Load GeoPHP.
  geophp_load();

  // Set BCMath scale.
  farm_map_set_bcscale();

  // Build a LineString and calculate the center point.
  $line = new LineString(array($p1, $p2));
  $centroid = $line->centroid();

  // Calculate the length of latitude and longitude degrees at the centroid.
  $lon_deg_len = farm_map_lon_deg_len($centroid->getY());
  $lat_deg_len = farm_map_lat_deg_len($centroid->getY());

  // If BCMath is available, use that. Otherwise, use normal PHP float
  // operations.
  if (geoPHP::bcmathInstalled()) {
    $length = bcsqrt(
      bcadd(
        bcpow(bcmul(bcsub($p1->getX(), $p2->getX()), $lon_deg_len), '2'),
        bcpow(bcmul(bcsub($p1->getY(), $p2->getY()), $lat_deg_len), '2')
      )
    );
  }
  else {
    $length = sqrt(pow((($p1->getX() - $p2->getX()) * $lon_deg_len), 2) + pow((($p1->getY() - $p2->getY()) * $lat_deg_len), 2));
  }

  // Reset BCMath scale.
  farm_map_reset_bcscale();

  // Return the length as a string.
  return (string) $length;
}

/**
 * Calculate the length of a LineString in meters.
 *
 * @param LineString $line
 *   The line to measure.
 *
 * @return string
 *   Returns the length of the line as a string, in meters.
 */
function farm_map_line_length($line) {

  // Load GeoPHP.
  geophp_load();

  // Set BCMath scale.
  farm_map_set_bcscale();

  // Start with a length of zero.
  $length = 0;

  // Iterate through the points.
  foreach ($line->getPoints() as $delta => $point) {

    // Attempt to load the previous point.
    $previous_point = $line->geometryN($delta);

    // If a previous point is available
    if ($previous_point) {

      // If BCMath is available, use that. Otherwise, use normal PHP float
      // operations.
      if (geoPHP::bcmathInstalled()) {
        $length = bcadd($length, farm_map_distance($previous_point, $point));
      }
      else {
        $length += farm_map_distance($previous_point, $point);
      }
    }
  }

  // Reset BCMath scale.
  farm_map_reset_bcscale();

  // Return the length as a string.
  return (string) $length;
}

/**
 * Calculate the area of a Polygon in square meters.
 *
 * @param Polygon $polygon
 *   The polygon to measure.
 *
 * @return string
 *   Returns the area of the polygon as a string, in  square meters.
 */
function farm_map_polygon_area($polygon) {

  // Load GeoPHP.
  geophp_load();

  // If the geometry is not a polygon, bail.
  if ($polygon->geometryType() != 'Polygon' || $polygon->components[0]->geometryType() != 'LineString') {
    return $polygon;
  }

  // Set BCMath scale.
  farm_map_set_bcscale();

  // We're going to do a pseudo-projection of the polygon into a coordinate
  // system that is measured in meters, and then run a standard area calculation
  // on that. We'll do this by first finding the bounding box of the polygon,
  // and use the lower left point as origin. Then, we'll calculate the latitude
  // and longitude lengths of the polygon's centroid point, and use those to
  // calculate the new point positions.

  // Get the bounding box of the polygon.
  $bbox = $polygon->getBBox();

  // Create an origin point.
  $origin = new Point($bbox['minx'], $bbox['miny']);

  // Get the polygon's centroid point.
  $centroid = $polygon->centroid();

  // Calculate the latitude/longitude degree lengths at the centroid point.
  $lon_deg_len = farm_map_lon_deg_len($centroid->getY());
  $lat_deg_len = farm_map_lat_deg_len($centroid->getY());

  // Iterate through the polygon's points and map them to new points.
  $line = $polygon->components[0];
  $new_points = array();
  foreach ($line->getPoints() as $delta => $point) {

    // Calculate the distance between the point and origin.
    $distance_x = $point->getX() - $origin->getX();
    $distance_y = $point->getY() - $origin->getY();

    // Multiply distances by latitude/longitude degree lengths to get new point.
    $new_x = $distance_x * $lon_deg_len;
    $new_y = $distance_y * $lat_deg_len;

    // Add the new point.
    $new_points[] = new Point($new_x, $new_y);
  }

  // Construct a new polygon.
  $new_polygon = new Polygon(array(new LineString($new_points)));

  // Calculate the area of the new polygon.
  $area = $new_polygon->area();

  // Reset BCMath scale.
  farm_map_reset_bcscale();

  // Return the area as a string.
  return (string) $area;
}
