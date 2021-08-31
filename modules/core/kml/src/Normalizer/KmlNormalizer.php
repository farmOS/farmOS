<?php

namespace Drupal\farm_kml\Normalizer;

use Drupal\farm_location\GeometryWrapper;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes GeometryWrapper objects into array for the Kml encoder.
 *
 * @see \Drupal\farm_kml\Encoder\Kml
 */
class KmlNormalizer implements NormalizerInterface, DenormalizerInterface {

  /**
   * The supported format.
   */
  const FORMAT = 'geometry_kml';

  /**
   * The supported type to denormalize to.
   */
  const TYPE = 'geometry_wrapper';

  /**
   * The GeoPHP service.
   *
   * @var \Drupal\geofield\GeoPHP\GeoPHPInterface
   */
  protected $geoPHP;

  /**
   * KMLNormalizer constructor.
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
  public function normalize($object, $format = NULL, array $context = []) {

    /** @var \Drupal\farm_location\GeometryWrapper $object */
    // Convert the geometry to KML.
    $kml_string = $object->geometry->out('kml');

    // Parse the KML string into an XML object.
    // This is necessary so that we can encode the KML into XML with the
    // rest of the asset data.
    $kml = simplexml_load_string($kml_string);
    $kml_name = $kml->getName();
    $kml_value = $kml->children();

    // Build a placemark definition.
    $placemark = [
      '#' => [
        $kml_name => $kml_value,
      ],
    ];

    // Add an ID if provided.
    if (isset($object->properties['id'])) {
      $placemark['@id'] = $object->properties['id'];
    }

    // Add standard KML properties if provided.
    $properties = $this->supportedProperties();
    foreach ($properties as $property_name) {
      if (isset($object->properties[$property_name])) {
        $placemark['#'][$property_name] = $object->properties[$property_name];
      }
    }

    return $placemark;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {

    // First check if the format is supported.
    if ($format !== static::FORMAT) {
      return FALSE;
    }

    // Change data to an array.
    if (!is_array($data)) {
      $data = [$data];
    }

    // Ensure all items are GeometryWrappers.
    $invalid_count = count(array_filter($data, function ($object) {
      return !$object instanceof GeometryWrapper;
    }));
    return $invalid_count === 0;
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $type, $format = NULL, array $context = []) {

    // Build array of geometry wrappers to return.
    $geometries = [];
    foreach ($data as $placemark) {

      // Skip if there is no XML key.
      if (empty($placemark['xml'])) {
        continue;
      }

      // Load KML into a Geometry object.
      $geometry = $this->geoPHP->load($placemark['xml'], 'kml');

      // Build properties.
      $properties = [];

      // Add an ID if provided.
      if (isset($placemark['@attributes']['id'])) {
        $properties['id'] = $placemark['@attributes']['id'];
      }

      // Add standard KML properties if included.
      $keys = $this->supportedProperties();
      foreach ($keys as $property_name) {
        if (isset($placemark[$property_name])) {
          $properties[$property_name] = $placemark[$property_name];
        }
      }

      $wrapper = new GeometryWrapper($geometry, $properties);
      $geometries[] = $wrapper;
    }
    return $geometries;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDenormalization($data, $type, $format = NULL) {
    return $type === static::TYPE && $format === static::FORMAT;
  }

  /**
   * Define supported properties.
   *
   * @return string[]
   *   An array of property names (strings).
   */
  protected function supportedProperties() {
    return ['name', 'entity_type', 'bundle', 'internal_id', 'description'];
  }

}
