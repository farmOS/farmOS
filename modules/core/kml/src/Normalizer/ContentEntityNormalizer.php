<?php

namespace Drupal\farm_kml\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes content entities as KML placemarks to be encoded.
 *
 * The entity's geofield name must be provided with $context['geofield'].
 */
class ContentEntityNormalizer implements NormalizerInterface {

  const FORMAT = 'kml';

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

    // Collect geometries as placemark definitions.
    $placemarks = [];

    // Bail if no geofield field is provided.
    if (empty($context['geofield'])) {
      return $placemarks;
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

        // Convert WKT to KML string.
        $geometry = $this->geoPHP->load($wkt, 'wkt');
        $kml_string = $geometry->out('kml');

        // Parse the KML string into an XML object.
        // This is necessary so that we can encode the KML into XML with the
        // rest of the asset data.
        $kml = simplexml_load_string($kml_string);
        $kml_name = $kml->getName();
        $kml_value = $kml->children();

        // Build a placemark definition.
        $placemark = [
          '@id' => $entity->getEntityTypeId() . '-' . $entity->id(),
          '#' => [
            'name' => htmlspecialchars($entity->label()),
            $kml_name => $kml_value,
          ],
        ];

        // Add entity notes as the KML description.
        if ($entity->hasField('notes')) {
          $notes = $entity->get('notes')->first()->getValue();
          if (!empty($notes['value'])) {
            $placemark['#']['description'] = $notes['value'];
          }
        }

        $placemarks[] = $placemark;
      }
    }

    return $placemarks;
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

    // Count how many objects are not content entities.
    $invalid_count = count(array_filter($data, function ($object) {
      return !$object instanceof ContentEntityInterface;
    }));

    // Ensure all items are content entities.
    return $invalid_count === 0;
  }

}
