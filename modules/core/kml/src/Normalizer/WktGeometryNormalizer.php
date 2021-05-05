<?php

namespace Drupal\farm_kml\Normalizer;

use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Deormalizes KML placemarks into arrays of WKT geometries.
 *
 * @todo Implement normalizer method for WKT geometry.
 *
 * @see \Drupal\farm_kml\Encoder\Kml
 */
class WktGeometryNormalizer implements DenormalizerInterface {

  const FORMAT = 'kml';
  const TYPE = 'wkt';

  /**
   * The GeoPHP service.
   *
   * @var \Drupal\geofield\GeoPHP\GeoPHPInterface
   */
  protected $geoPHP;

  /**
   * ContentEntityNormalizer constructor.
   *
   * @param \Drupal\geofield\GeoPHP\GeoPHPInterface $geo_PHP
   *   The GeoPHP service.
   */
  public function __construct(GeoPHPInterface $geo_PHP) {
    $this->geoPHP = $geo_PHP;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $type, $format = NULL, array $context = []) {

    // Build array of geometries to return.
    $geometries = [];
    foreach ($data as $placemark) {

      // Skip if there is no XML key.
      if (empty($placemark['xml'])) {
        continue;
      }

      // Build geometry array.
      $geometry = [];

      // Convert KML to WKT.
      $geom = $this->geoPHP->load($placemark['xml'], 'kml');
      $wkt = $geom->out('wkt');
      $geometry['wkt'] = $wkt;

      // Include name and description.
      $geometry['name'] = $placemark['name'];
      $geometry['description'] = $placemark['description'];

      $geometries[] = $geometry;
    }
    return $geometries;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDenormalization($data, $type, $format = NULL) {
    return $type === static::TYPE && $format === static::FORMAT;
  }

}
