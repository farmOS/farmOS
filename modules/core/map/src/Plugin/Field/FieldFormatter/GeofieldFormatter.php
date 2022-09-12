<?php

namespace Drupal\farm_map\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the map 'geofield' formatter.
 *
 * @FieldFormatter(
 *   id = "farm_map_geofield",
 *   label = @Translation("farmOS Map"),
 *   field_types = {
 *     "geofield"
 *   }
 * )
 */
class GeofieldFormatter extends FormatterBase {

  /**
   * The geofield.geophp service.
   *
   * @var \Drupal\geofield\GeoPHP\GeoPHPInterface
   */
  protected $geoPhp;

  /**
   * Constructs a FormatterBase object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\geofield\GeoPHP\GeoPHPInterface $geo_php
   *   The geofield geophp service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, GeoPHPInterface $geo_php) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->geoPhp = $geo_php;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('geofield.geophp'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    // Build a render element.
    $element = [];

    // First check to see if we have any value and remove any unset deltas.
    foreach ($items as $delta => $item) {
      if (empty($item->get('value')->getValue())) {
        unset($items[$delta]);
      }
    }

    // If there are no items, stop here. We won't show anything.
    if ($items->isEmpty()) {
      return $element;
    }

    // Create array of features.
    $features = [];
    foreach ($items as $delta) {

      // Get the field value.
      $value = $delta->get('value')->getValue();

      // Convert to WKT.
      $geom = $this->geoPhp->load($value);
      $features[] = $geom->out('wkt');
    }

    // If there are no features at this point, bail.
    if (empty($features)) {
      return $element;
    }

    // Build a map for each item.
    foreach ($features as $delta => $feature) {
      $element[$delta] = [
        '#type' => 'farm_map',
        '#map_type' => 'geofield',
        '#map_settings' => [
          'wkt' => $feature,
          'behaviors' => [
            'wkt' => [
              'zoom' => TRUE,
            ],
          ],
        ],
      ];
    }

    return $element;
  }

}
