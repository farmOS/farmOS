<?php

namespace Drupal\farm_map\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;

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
      // @todo Does this have to return a tree structure?
      '#tree' => TRUE,
      '#process' => [
        [$class, 'processElement'],
      ],
      '#pre_render' => [
        [$class, 'preRenderGroup'],
      ],
      // @todo Add validation.
//      '#element_validate' => [
//        [$class, 'elementValidate'],
//      ],
      '#theme_wrappers' => ['fieldset'],
      // Display descriptions above the map by default.
      '#description_display' => 'before',
      '#map_type' => 'geofield_widget',
      '#default_value' => '',
      '#display_raw_geometry' => TRUE,
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

    // Define the map render array.
    // @todo Does this have to return a tree structure?
    $element['#tree'] = TRUE;
    $element['map'] = [
      '#type' => 'farm_map',
      '#map_type' => $element['#map_type'],
      '#map_settings' => [
        'behaviors' => [
          'wkt' => [
            'edit' => TRUE,
            'zoom' => TRUE,
          ],
        ],
      ],
    ];

    // Add a textarea for the WKT value.
    $display_raw_geometry = $element['#display_raw_geometry'];
    $element['value'] = [
      '#type' => $display_raw_geometry ? 'textarea' : 'hidden',
      '#title' => t('Geometry'),
      '#attributes' => [
        'data-map-geometry-field' => TRUE,
      ],
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
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input === FALSE) {
      return $element['#default_value'] ?: '';
    }

    if ($input['value']) {
      return $input['value'];
    }

    return NULL;
  }

}
