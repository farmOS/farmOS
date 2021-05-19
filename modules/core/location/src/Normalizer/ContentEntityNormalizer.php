<?php

namespace Drupal\farm_location\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\farm_location\GeometryWrapper;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * Normalizes content entities as arrays for encoding into geometry files.
 *
 * The entity's geofield name must be provided with $context['geofield'].
 */
class ContentEntityNormalizer implements NormalizerInterface, SerializerAwareInterface {

  use SerializerAwareTrait;

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
  public function normalize($object, $format = NULL, array $context = []) {

    // Build GeometryWrapper objects.
    $geometries = [];

    // Bail if no geofield field is provided.
    if (empty($context['geofield'])) {
      return $geometries;
    }

    $geofield = $context['geofield'];
    $entities = is_array($object) ? $object : [$object];
    foreach ($entities as $entity) {
      // If the entity doesn't have the configured geofield field, bail.
      if (!$entity->hasField($geofield)) {
        continue;
      }

      $field_value = $entity->get($geofield)->first();
      $wkt = $field_value->get('value')->getValue();
      if (!empty($wkt)) {

        // Load WKT as a GeoPHP Geometry object.
        $geometry = $this->geoPHP->load($wkt, 'wkt');

        // Build geometry properties.
        $properties = [
          'id' => $entity->getEntityTypeId() . '-' . $entity->id(),
          'name' => htmlspecialchars($entity->label()),
        ];

        // Add entity notes as the description.
        if ($entity->hasField('notes')) {
          $notes = $entity->get('notes')->first()->getValue();
          if (!empty($notes['value'])) {
            $properties['description'] = $notes['value'];
          }
        }

        // Create the GeometryWrapper.
        $geometry_wrapper = new GeometryWrapper($geometry, $properties);
        $geometries[] = $geometry_wrapper;
      }
    }

    // Normalize the GeometryWrapper objects to their target type.
    return array_map(function (GeometryWrapper $geom) use ($format, $context) {
      return $this->serializer->normalize($geom, $format, $context);
    }, $geometries);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {

    // First check if the format is supported.
    // Only formats that are prefixed with "geometry_" are supported.
    // This makes it easier for other modules to provide geometry encoders.
    if (strpos($format, 'geometry_') !== 0) {
      return FALSE;
    }

    // Change data to an array.
    if (!is_array($data)) {
      $data = [$data];
    }

    // Count how many objects are not content entities.
    $invalid_count = count(array_filter($data, function ($object) {
      return !$object instanceof ContentEntityInterface;
    }));

    // Ensure all items are content entities.
    return $invalid_count === 0;
  }

}
