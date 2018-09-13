<?php
/**
 * @file
 * Control: OLGeocoder.
 */

namespace Drupal\farm_map\Plugin\Control\OLGeocoder;

use Drupal\openlayers\Types\Control;
use Drupal\openlayers\Types\ObjectInterface;

/**
 * Class OLGeocoder.
 *
 * @OpenlayersPlugin(
 *  id = "OLGeocoder",
 *  description = "Geocoder Nominatim for OpenLayers"
 * )
 */
class OLGeocoder extends Control {

  /**
   * {@inheritdoc}
   */
  public function preBuild(array &$build, ObjectInterface $context = NULL) {
    parent::preBuild($build, $context);
    $options = array(
      'type' => 'external',
      'weight' => 100,
    );
    drupal_add_js('https://cdn.jsdelivr.net/npm/ol-geocoder', $options);
    drupal_add_css('https://cdn.jsdelivr.net/npm/ol-geocoder@latest/dist/ol-geocoder.min.css', $options);
  }
}
