<?php

namespace Drupal\farm_kml\Normalizer;

use Drupal\farm_location\GeometryWrapper;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes GeometryWrapper objects into array for the Kml encoder.
 */
class KmlNormalizer implements NormalizerInterface {

  const FORMAT = 'geometry_kml';

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
    $properties = ['name', 'description'];
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

}
