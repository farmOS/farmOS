<?php

namespace Drupal\farm_map\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\geofield\Plugin\Field\FieldFormatter\LatLonFormatter;

/**
 * Plugin implementation of the 'geofield_centroid' formatter.
 *
 * @FieldFormatter(
 *   id = "geofield_centroid",
 *   label = @Translation("Centroid"),
 *   field_types = {
 *     "geofield"
 *   }
 * )
 */
class CentroidFormatter extends LatLonFormatter {

  /**
   * Helper function to get the formatter settings options.
   *
   * @return array
   *   The formatter settings options.
   */
  protected function formatOptions() {

    // Get parent options.
    $options = parent::formatOptions();

    // Define a WKT Point format.
    $options['wkt'] = $this->t('WKT Point');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $output = ['#markup' => ''];
      $geom = $this->geoPhpWrapper->load($item->value);
      if (empty($geom)) {
        continue;
      }
      $centroid = $geom->centroid();
      if (!empty($centroid)) {
        /** @var \Point $centroid */
        if ($this->getOutputFormat() == 'decimal') {
          $output = [
            '#theme' => 'geofield_latlon',
            '#lat' => $centroid->y(),
            '#lon' => $centroid->x(),
          ];
        }
        elseif ($this->getOutputFormat() == 'wkt') {
          $output = [
            '#markup' => "POINT({$centroid->x()} {$centroid->y()})",
          ];
        }
        else {
          $components = $this->getDmsComponents($centroid);
          $output = [
            '#theme' => 'geofield_dms',
            '#components' => $components,
          ];
        }
      }
      $elements[$delta] = $output;
    }

    return $elements;
  }

}
