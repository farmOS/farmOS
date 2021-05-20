<?php

namespace Drupal\farm_map\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\FileInterface;
use Drupal\geofield\Plugin\Field\FieldWidget\GeofieldBaseWidget;

/**
 * Plugin implementation of the map 'geofield' widget.
 *
 * @FieldWidget(
 *   id = "farm_map_geofield",
 *   label = @Translation("farmOS Map"),
 *   field_types = {
 *     "geofield"
 *   }
 * )
 */
class GeofieldWidget extends GeofieldBaseWidget {

  /**
   * Supported GeoPHP file types.
   *
   * @var string[]
   *   GeoPHP type keyed by file extension.
   */
  public static $geoPhpTypes = [
    'geojson' => 'geojson',
    'gpx' => 'gpx',
    'kml' => 'kml',
    'kmz' => 'kml',
    'wkb' => 'wkb',
    'wkt' => 'wkt',
  ];

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'populate_file_field' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['populate_file_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('File field to populate geometry from.'),
      '#default_value' => $this->getSetting('populate_file_field'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    // Wrap the map in a collapsible details element.
    $field_name = $this->fieldDefinition->getName();
    $field_wrapper_id = $field_name . '_wrapper';
    $element['#type'] = 'details';
    $element['#title'] = $this->t('Geometry');
    $element['#open'] = TRUE;
    $element['#prefix'] = '<div id="' . $field_wrapper_id . '">';
    $element['#suffix'] = '</div>';

    // Get the current form state value. Prioritize form state over field value.
    $form_value = $form_state->getValue([$field_name, $delta, 'value']);
    $field_value = $items[$delta]->value;
    $current_value = $form_value ?? $field_value;

    // Define the map render array.
    $element['map'] = [
      '#type' => 'farm_map',
      '#map_type' => 'geofield_widget',
      '#map_settings' => [
        'wkt' => $current_value,
        'behaviors' => [
          'wkt' => [
            'edit' => TRUE,
            'zoom' => TRUE,
          ],
        ],
      ],
    ];

    // Add a textarea for the WKT value.
    $element['value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Geometry'),
      '#default_value' => $current_value,
    ];

    // Add an option to populate geometry using files field.
    // The "populate_file_field" field setting must be configured and the
    // field must be included in the current form.
    $populate_file_field = $this->getSetting('populate_file_field');
    if (!empty($populate_file_field) && !empty($form[$populate_file_field])) {
      $element['trigger'] = [
        '#type' => 'submit',
        '#value' => $this->t('Find using files field'),
        '#submit' => [[$this, 'fileParse']],
        '#ajax' => [
          'wrapper' => $field_wrapper_id,
          'callback' => [$this, 'fileCallback'],
          'message' => $this->t('Working...'),
        ],
        '#states' => [
          'disabled' => [
            ':input[name="' . $populate_file_field . '[0][fids]"]' => ['empty' => TRUE],
          ],
        ],
      ];
    }

    return $element;
  }

  /**
   * Submit function to parse geometries from uploaded files.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function fileParse(array &$form, FormStateInterface $form_state) {

    // Bail if no populate file field is not configured.
    $populate_file_field = $this->getSetting('populate_file_field');
    if (empty($populate_file_field)) {
      return;
    }

    // Get the form field element.
    $triggering_element = $form_state->getTriggeringElement();
    $element = NestedArray::getValue($form, array_slice($triggering_element['#array_parents'], 0, -1));

    // Load the uploaded files.
    $uploaded_files = $form_state->getValue($populate_file_field);
    if (!empty($uploaded_files)) {

      // Get file IDs.
      $file_ids = array_reduce($uploaded_files, function ($carry, $file) {
        return array_merge($carry, array_values($file['fids']));
      }, []);

      // Load and process each file.
      /** @var \Drupal\file\Entity\File[] $files */
      $files = \Drupal::entityTypeManager()->getStorage('file')->loadMultiple($file_ids);

      // @todo Support multiple files. Combine geometries?
      // @todo Support geometry field with > 1 cardinality.
      $wkt = '';
      if (!empty($files)) {

        // Check the first file.
        $file = reset($files);
        $geophp_type = $this->getGeoPhpType($file);

        // Bail if the file is not a supported format.
        if ($geophp_type === FALSE) {
          $this->messenger()->addWarning(
            $this->t('%filename is not a supported geometry file format. Supported formats: %formats',
            ['%filename' => $file->getFilename(), '%formats' => implode(', ', array_keys(static::$geoPhpTypes))]
          ));
          return;
        }

        // Try to parse geometry using the specified geoPHP type.
        $data = file_get_contents($file->getFileUri());
        if ($geom = $this->geoPhpWrapper->load($data, $geophp_type)) {
          $wkt = $geom->out('wkt');
        }
      }

      // Bail if no geometry was parsed.
      if (empty($wkt)) {
        $this->messenger()->addWarning($this->t('No geometry could be parsed from %filename.', ['%filename' => $file->getFilename()]));
        return;
      }

      // Unset the current geometry value from the user input.
      $field_name = $this->fieldDefinition->getName();
      $delta = $element['#delta'];
      $user_input = $form_state->getUserInput();
      unset($user_input[$field_name][$delta]['value']);
      $form_state->setUserInput($user_input);

      // Set the new form value.
      $form_state->setValue([$field_name, $delta, 'value'], $wkt);

      // Rebuild the form so the map widget is rebuilt with the new value.
      $form_state->setRebuild(TRUE);
    }
  }

  /**
   * AJAX callback for the find using files field button.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array|mixed|null
   *   The map form element to replace
   */
  public function fileCallback(array &$form, FormStateInterface $form_state) {
    // Return the rebuilt map form field field element.
    $triggering_element = $form_state->getTriggeringElement();
    return NestedArray::getValue($form, array_slice($triggering_element['#array_parents'], 0, -1));
  }

  /**
   * Helper function to check if the file extension is a supported geometry.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file to check.
   *
   * @return string|false
   *   The GeoPHP type or FALSE.
   */
  private function getGeoPhpType(FileInterface $file) {

    // Get the file extension.
    $matches = [];
    if (preg_match('/(?<=\.)[^.]+$/', $file->getFilename(), $matches) && isset($matches[0])) {
      // Return the associated GeoPHP type.
      if (isset(self::$geoPhpTypes[$matches[0]])) {
        return self::$geoPhpTypes[$matches[0]];
      }
    }

    // Otherwise the file extension is not a valid GeoPHP geometry type.
    return FALSE;
  }

}
