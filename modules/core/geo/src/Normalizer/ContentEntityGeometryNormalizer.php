<?php

namespace Drupal\farm_geo\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\farm_geo\GeometryWrapper;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * Normalizes content entities into arrays of GeometryWrapper objects.
 *
 * This can be used for encoding entities into geospatial files.
 *
 * The entity's geofield name must be provided with $context['geofield'].
 *
 * @see \Drupal\farm_geo\GeometryWrapper
 */
class ContentEntityGeometryNormalizer implements NormalizerInterface, SerializerAwareInterface {

  use SerializerAwareTrait;

  /**
   * The GeoPHP service.
   *
   * @var \Drupal\geofield\GeoPHP\GeoPHPInterface
   */
  protected $geoPHP;

  /**
   * ContentEntityGeometryNormalizer constructor.
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

    // Check the entity geofield.
    $geofield = $context['geofield'];
    $entity = $object;
    if (!$entity->hasField($geofield)) {
      return NULL;
    }

    // If the geofield is empty, bail.
    if ($entity->get($geofield)->isEmpty()) {
      return NULL;
    }

    // Check WKT value.
    $field_value = $entity->get($geofield)->first();
    $wkt = $field_value->get('value')->getValue();
    if (empty($wkt)) {
      return NULL;
    }

    // Load WKT as a GeoPHP Geometry object.
    $geometry = $this->geoPHP->load($wkt, 'wkt');

    // Build geometry properties.
    $properties = [
      'id' => $entity->uuid(),
      'name' => htmlspecialchars($entity->label()),
      'entity_type' => $entity->getEntityTypeId(),
      'bundle' => $entity->bundle(),
      'internal_id' => $entity->id(),
    ];

    // Add entity notes as the description.
    if ($entity->hasField('notes')) {
      $notes = $entity->get('notes')->first()->getValue();
      if (!empty($notes['value'])) {
        $properties['description'] = $notes['value'];
      }
    }

    // Normalize the GeometryWrapper object to the target type.
    $geometry_wrapper = new GeometryWrapper($geometry, $properties);
    return $this->serializer->normalize($geometry_wrapper, $format, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {

    // Check that the data is a content entity.
    // Only formats that are prefixed with "geometry_" are supported.
    // This makes it easier for other modules to provide geometry encoders.
    return $data instanceof ContentEntityInterface && strpos($format, 'geometry_') === 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getSupportedTypes(?string $format): array {
    return [
      ContentEntityInterface::class => TRUE,
    ];
  }

}
