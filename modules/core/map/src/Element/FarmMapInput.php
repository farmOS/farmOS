<?php

namespace Drupal\farm_map\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\geofield\GeoPHP\GeoPHPWrapper;

/**
 * Form element that returns WKT rendered in a map.
 *
 * @FormElement("farm_map_input")
 */
class FarmMapInput extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = static::class;
    return [
      '#input' => TRUE,
      '#process' => [
        [$class, 'processElement'],
      ],
      '#pre_render' => [
        [$class, 'preRenderGroup'],
      ],
      '#element_validate' => [
        [$class, 'elementValidate'],
      ],
      '#theme_wrappers' => ['form_element'],
      // Display descriptions above the map by default.
      '#description_display' => 'before',
      '#map_type' => 'default',
      '#map_settings' => [],
      '#behaviors' => [],
      '#default_value' => '',
      '#display_raw_geometry' => FALSE,
      '#disabled' => FALSE,
    ];
  }

  /**
   * Generates the form element.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   element. Note that $element must be taken by reference here, so processed
   *   child elements are taken over into $form_state.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $complete_form
   *   The complete form structure.
   *
   * @return array
   *   The processed element.
   */
  public static function processElement(array $element, FormStateInterface $form_state, array &$complete_form) {
    $element['#tree'] = TRUE;

    // Merge provided map behaviors into defaults. Enable wkt and input
    // behaviors if #disabled is not TRUE.
    $default_behaviors = !$element['#disabled'] ? ['wkt', 'input'] : [];
    $behaviors = array_merge($default_behaviors, $element['#behaviors']);

    // Recursively merge provided map settings into defaults.
    $map_settings = array_merge_recursive([
      'behaviors' => [
        'wkt' => [
          'edit' => !$element['#disabled'],
          'zoom' => TRUE,
        ],
      ],
    ], $element['#map_settings']);

    // Define the map render array.
    $element['map'] = [
      '#type' => 'farm_map',
      '#map_type' => $element['#map_type'],
      '#map_settings' => $map_settings,
      '#behaviors' => $behaviors,
    ];

    // Add a textarea for the WKT value.
    $display_raw_geometry = $element['#display_raw_geometry'];
    $element_title = $element['#title'] ?? t('Geometry');
    if ($display_raw_geometry) {
      $element_title .= ' ' . t('WKT');
    }
    $element['value'] = [
      '#type' => $display_raw_geometry ? 'textarea' : 'hidden',
      '#title' => $element_title,
      '#title_display' => 'invisible',
      '#attributes' => [
        'data-map-geometry-field' => TRUE,
      ],
      '#disabled' => $element['#disabled'],
      '#required' => $element['#required'],
    ];

    // Add default value if provided.
    if (!empty($element['#default_value'])) {
      $element['map']['#map_settings']['wkt'] = $element['#default_value'];
      $element['value']['#default_value'] = $element['#default_value'];
    }

    // Return the element.
    return $element;
  }

  /**
   * Validates the form element.
   */
  public static function elementValidate(&$element, FormStateInterface $form_state, &$complete_form) {

    // Validate that the geometry data is valid by attempting to load it into
    // GeoPHP. This uses the same logic and error message as the geofield
    // module's validation constraint.
    // @see Drupal\geofield\Plugin\Validation\Constraint\GeoConstraint
    // @see Drupal\geofield\Plugin\Validation\Constraint\GeoConstraintValidator
    $value = $element['value']['#value'];
    if (!empty($value)) {
      $geophp = new GeoPHPWrapper();
      $valid_geometry = TRUE;
      try {
        if (!$geophp->load($value)) {
          $valid_geometry = FALSE;
        }
      }
      catch (\Exception $e) {
        $valid_geometry = FALSE;
      }
      if (!$valid_geometry) {
        $form_state->setError($element, t('"@value" is not a valid geospatial content.', ['@value' => $value]));
      }
    }

    // Save the WKT string value to the overall element.
    $form_state->setValueForElement($element, $value);
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input === FALSE) {
      return $element['#default_value'] ?: '';
    }
    return $input;
  }

}
