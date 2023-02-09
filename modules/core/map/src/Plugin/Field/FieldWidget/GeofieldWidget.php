<?php

namespace Drupal\farm_map\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_geo\Traits\WktTrait;
use Drupal\file\FileInterface;
use Drupal\geofield\GeoPHP\GeoPHPInterface;
use Drupal\geofield\Plugin\Field\FieldWidget\GeofieldBaseWidget;
use Drupal\geofield\Plugin\GeofieldBackendManager;
use Drupal\geofield\WktGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

  use WktTrait;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
   * GeofieldWidget constructor.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\geofield\GeoPHP\GeoPHPInterface $geophp_wrapper
   *   The geoPhpWrapper.
   * @param \Drupal\geofield\WktGeneratorInterface $wkt_generator
   *   The WKT format Generator service.
   * @param \Drupal\geofield\Plugin\GeofieldBackendManager $geofield_backend_manager
   *   The geofieldBackendManager.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   The file system service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, GeoPHPInterface $geophp_wrapper, WktGeneratorInterface $wkt_generator, GeofieldBackendManager $geofield_backend_manager, FileSystem $file_system, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $geophp_wrapper, $wkt_generator, $geofield_backend_manager);
    $this->fileSystem = $file_system;
    $this->entityTypeManager = $entity_type_manager;
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
      $configuration['third_party_settings'],
      $container->get('geofield.geophp'),
      $container->get('geofield.wkt_generator'),
      $container->get('plugin.manager.geofield_backend'),
      $container->get('file_system'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display_raw_geometry' => TRUE,
      'populate_file_field' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['display_raw_geometry'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display raw geometry'),
      '#default_value' => $this->getSetting('display_raw_geometry'),
    ];

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

    // Use the farm_map_input form element.
    $element['#type'] = 'farm_map_input';

    // Use the geofield map type.
    $element['#map_type'] = 'geofield';

    // Wrap in a fieldset.
    $element['#theme_wrappers'] = ['fieldset'];

    // Wrap the map with a unique id for populating from files.
    $field_name = $this->fieldDefinition->getName();
    $field_wrapper_id = Html::getUniqueId($field_name . '_wrapper');
    $element['#prefix'] = '<div id="' . $field_wrapper_id . '">';
    $element['#suffix'] = '</div>';

    // Get the current form state value. Prioritize form state over field value.
    $form_value = $form_state->getValue([$field_name, $delta]);
    $field_value = $items[$delta]->value;
    $current_value = $form_value['value'] ?? $field_value;
    $element['#default_value'] = $current_value;

    // Configure to display raw geometry.
    $display_raw_geometry = $this->getSetting('display_raw_geometry');
    $element['#display_raw_geometry'] = $display_raw_geometry;

    // Add an option to populate geometry using files field.
    // The "populate_file_field" field setting must be configured and the
    // field must be included in the current form.
    $populate_file_field = $this->getSetting('populate_file_field');
    if (!empty($populate_file_field) && !empty($form[$populate_file_field])) {
      $element['trigger'] = [
        '#type' => 'submit',
        '#value' => $this->t('Import geometry from uploaded files'),
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
        '#weight' => 10,
      ];
    }

    // Override the element validation to prevent transformation of the value
    // from array to string, and because Geofields already perform the same
    // geometry validation.
    // @see \Drupal\geofield\Plugin\Validation\GeoConstraintValidator.
    $element['#element_validate'] = [];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      $values[$delta]['value'] = $this->geofieldBackendValue($value['value']);
    }
    return $values;
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
      $files = $this->entityTypeManager->getStorage('file')->loadMultiple($file_ids);

      // @todo Support geometry field with > 1 cardinality.
      $wkt_strings = [];
      if (!empty($files)) {
        foreach ($files as $file) {

          // Get the geometry type.
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
          $path = $file->getFileUri();
          if ($geophp_type == 'kml' && $file->getMimeType() === 'application/vnd.google-earth.kmz' && extension_loaded('zip')) {
            $path = 'zip://' . $this->fileSystem->realpath($path) . '#doc.kml';
          }
          $data = file_get_contents($path);
          if ($geom = $this->geoPhpWrapper->load($data, $geophp_type)) {
            $wkt_strings[] = $geom->out('wkt');
          }
        }
      }

      // Merge WKT geometries into a single geometry collection.
      $wkt = '';
      if (!empty($wkt_strings)) {
        if (count($wkt_strings) > 1) {
          $wkt = $this->combineWkt($wkt_strings);
        }
        else {
          $wkt = reset($wkt_strings);
        }
      }

      // Bail if no geometry was parsed.
      if (empty($wkt)) {
        $this->messenger()->addWarning($this->t('No geometry could be parsed from files.'));
        return;
      }

      // Unset the current geometry value from the user input.
      $field_name = $this->fieldDefinition->getName();
      $delta = $element['#delta'];
      $user_input = $form_state->getUserInput();
      unset($user_input[$field_name][$delta]);
      $form_state->setUserInput($user_input);

      // Set the new form value.
      $form_state->setValue([$field_name, $delta], ['value' => $wkt]);

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
