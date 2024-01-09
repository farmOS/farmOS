<?php

namespace Drupal\farm_field;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldException;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\entity\BundleFieldDefinition;

/**
 * Factory for generating farmOS field definitions.
 */
class FarmFieldFactory implements FarmFieldFactoryInterface {

  /**
   * Generate a base field definition.
   *
   * @param array $options
   *   An array of options.
   *
   * @return \Drupal\Core\Field\BaseFieldDefinition
   *   Returns a base field definition.
   */
  public function baseFieldDefinition(array $options = []): BaseFieldDefinition {
    $field = BaseFieldDefinition::create($options['type']);
    $this->buildFieldDefinition($field, $options);
    return $field;
  }

  /**
   * Generates a bundle field definition.
   *
   * @param array $options
   *   An array of options.
   *
   * @return \Drupal\entity\BundleFieldDefinition
   *   Returns a bundle field definition.
   */
  public function bundleFieldDefinition(array $options = []): BundleFieldDefinition {
    $field = BundleFieldDefinition::create($options['type']);
    $this->buildFieldDefinition($field, $options);
    return $field;
  }

  /**
   * Builds a field definition with farmOS opinions.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function buildFieldDefinition(BaseFieldDefinition &$field, array $options = []) {

    // Set label.
    if (!empty($options['label'])) {
      $field->setLabel($options['label']);
    }

    // Set description.
    if (!empty($options['description'])) {
      $field->setDescription($options['description']);
    }

    // Set computed.
    if (!empty($options['computed'])) {
      $field->setComputed(TRUE);
      $field->setClass($options['computed']);
    }

    // Make the field required, if specified.
    if (!empty($options['required'])) {
      $field->setRequired(TRUE);
    }

    // Make the field revisionable, unless told otherwise.
    if (empty($options['revisionable'])) {
      $field->setRevisionable(TRUE);
    }
    else {
      $field->setRevisionable(FALSE);
    }

    // Set cardinality, if specified.
    if (!empty($options['cardinality'])) {
      $field->setCardinality($options['cardinality']);
    }

    // Or, if `multiple` is set, set it to unlimited.
    elseif (!empty($options['multiple'])) {
      $field->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED);
    }

    // Otherwise, set cardinality to 1.
    else {
      $field->setCardinality(1);
    }

    // Only make the field translatable if specified.
    if (empty($options['translatable'])) {
      $field->setTranslatable(FALSE);
    }
    else {
      $field->setTranslatable(TRUE);
    }

    // Set the default value callback, if specified.
    if (!empty($options['default_value_callback'])) {
      $field->setDefaultValueCallback($options['default_value_callback']);
    }

    // Delegate to per-type helper functions to fill in more details.
    switch ($options['type']) {

      case 'boolean':
        $this->modifyBooleanField($field, $options);
        break;

      case 'decimal':
        $this->modifyDecimalField($field, $options);
        break;

      case 'email':
        $this->modifyEmailField($field, $options);
        break;

      case 'entity_reference':
        $this->modifyEntityReferenceField($field, $options);
        break;

      case 'entity_reference_revisions':
        $this->modifyEntityReferenceRevisionsField($field, $options);
        break;

      case 'file':
      case 'image':
        $this->modifyFileField($field, $options);
        break;

      case 'fraction':
        $this->modifyFractionField($field, $options);
        break;

      case 'geofield':
        $this->modifyGeofieldField($field, $options);
        break;

      case 'id_tag':
        $this->modifyIdTagField($field, $options);
        break;

      case 'integer':
        $this->modifyIntegerField($field, $options);
        break;

      case 'inventory':
        $this->modifyInventoryField($field, $options);
        break;

      case 'list_string':
        $this->modifyListStringField($field, $options);
        break;

      case 'string':
      case 'string_long':
        $this->modifyStringField($field, $options);
        break;

      case 'text_long':
        $this->modifyTextField($field, $options);
        break;

      case 'timestamp':
        $this->modifyTimestampField($field, $options);
        break;

      default:
        throw new FieldException('Unsupported field type.');

    }

    // Hide the field in form and view displays, if specified.
    // The hidden option can either be set to TRUE, which will hide it in both
    // form and view displays, or it can be set to "form" or "view", which will
    // only hide it in the form or view display.
    if (!empty($options['hidden'])) {
      $display_options = [
        'region' => 'hidden',
      ];
      if ($options['hidden'] === TRUE || $options['hidden'] === 'form') {
        $field->setDisplayOptions('form', $display_options);
      }
      if ($options['hidden'] === TRUE || $options['hidden'] === 'view') {
        $field->setDisplayOptions('view', $display_options);
      }
    }

    // Make the form and view displays configurable.
    $field->setDisplayConfigurable('form', TRUE);
    $field->setDisplayConfigurable('view', TRUE);

    // Override form and view display options, if specified.
    foreach (['form', 'view'] as $display_type) {
      $key = $display_type . '_display_options';
      if (isset($options[$key])) {
        $field->setDisplayOptions($display_type, $options[$key]);
      }
    }
  }

  /**
   * Boolean field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyBooleanField(BaseFieldDefinition &$field, array $options = []) {

    // Set the on/off labels.
    $field->setSetting('on_label', 'Yes');
    $field->setSetting('off_label', 'No');

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'settings' => [
        'display_label' => TRUE,
      ],
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'boolean',
      'settings' => [
        'format' => 'default',
        'format_custom_false' => '',
        'format_custom_true' => '',
      ],
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Decimal field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyDecimalField(BaseFieldDefinition &$field, array $options = []) {

    // Set the precision and scale, if specified.
    if (!empty($options['precision'])) {
      $field->setSetting('precision', $options['precision']);
    }
    if (!empty($options['scale'])) {
      $field->setSetting('scale', $options['scale']);
    }

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'number',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $view_display_options = [
      'label' => 'inline',
      'type' => 'number_decimal',
      'weight' => $options['weight']['view'] ?? 0,
    ];
    if (!empty($options['scale'])) {
      $view_display_options['settings']['scale'] = $options['scale'];
    }
    $field->setDisplayOptions('view', $view_display_options);
  }

  /**
   * Email field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyEmailField(BaseFieldDefinition &$field, array $options = []) {

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'email_default',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'email_mailto',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Entity reference field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyEntityReferenceField(BaseFieldDefinition &$field, array $options = []) {

    // If a target type is not specified, throw an exception.
    if (empty($options['target_type'])) {
      throw new FieldException('No target_type was specified.');
    }

    // Set the target type.
    $field->setSetting('target_type', $options['target_type']);

    // Build additional settings based on the target type.
    switch ($options['target_type']) {

      // Asset reference.
      case 'asset':
        if (!empty($options['target_bundle'])) {
          $handler = 'default:asset';
          $handler_settings = [
            'target_bundles' => [
              $options['target_bundle'] => $options['target_bundle'],
            ],
            'sort' => [
              'field' => '_none',
            ],
            'auto_create' => FALSE,
            'auto_create_bundle' => '',
          ];
        }
        else {
          $handler = 'views';
          $handler_settings = [
            'view' => [
              'view_name' => 'farm_asset_reference',
              'display_name' => 'entity_reference',
            ],
          ];
        }
        $form_display_options = [
          'type' => 'entity_reference_autocomplete',
          'weight' => $options['weight']['form'] ?? 0,
          'settings' => [
            'match_operator' => 'CONTAINS',
            'match_limit' => '10',
            'size' => '60',
            'placeholder' => '',
          ],
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_label',
          'weight' => $options['weight']['view'] ?? 0,
          'settings' => [
            'link' => TRUE,
          ],
        ];
        break;

      // Log.
      case 'log':
        $handler = 'default:log';
        $handler_settings = [
          'target_bundles' => NULL,
          'sort' => [
            'field' => 'name',
            'direction' => 'asc',
          ],
          'auto_create' => FALSE,
          'auto_create_bundle' => '',
        ];
        $form_display_options = [
          'type' => 'entity_reference_autocomplete',
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_label',
          'weight' => $options['weight']['view'] ?? 0,
          'settings' => [
            'link' => FALSE,
          ],
        ];
        break;

      // Term reference.
      case 'taxonomy_term':
        $handler = 'default:taxonomy_term';
        $handler_settings = [
          'target_bundles' => [
            $options['target_bundle'] => $options['target_bundle'],
          ],
          'sort' => [
            'field' => 'name',
            'direction' => 'asc',
          ],
          'auto_create' => FALSE,
          'auto_create_bundle' => '',
        ];

        // Auto create term reference if auto_create is enabled.
        if (!empty($options['auto_create'])) {
          $handler_settings['auto_create'] = TRUE;
          $handler_settings['auto_create_bundle'] = $options['target_bundle'];
        }

        $form_display_options = [
          'type' => 'entity_reference_autocomplete',
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_label',
          'weight' => $options['weight']['view'] ?? 0,
          'settings' => [
            'link' => TRUE,
          ],
        ];
        break;

      // User reference.
      case 'user':
        $handler = 'default:user';
        $handler_settings = [
          'include_anonymous' => FALSE,
          'filter' => [
            'type' => '_none',
          ],
          'target_bundles' => NULL,
          'sort' => [
            'field' => '_none',
          ],
          'auto_create' => FALSE,
        ];
        $form_display_options = [
          'type' => 'options_select',
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_label',
          'weight' => $options['weight']['view'] ?? 0,
          'settings' => [
            'link' => TRUE,
          ],
        ];
        break;

      // Data stream reference.
      case 'data_stream':
        $handler = 'default:data_stream';
        $handler_settings = [
          'filter' => [
            'type' => '_none',
          ],
          'target_bundles' => NULL,
          'sort' => [
            'field' => '_none',
          ],
          'auto_create' => FALSE,
        ];
        $form_display_options = [
          'type' => 'inline_entity_form_complex',
          'settings' => [
            'form_mode' => 'default',
            'revision' => TRUE,
            'override_labels' => FALSE,
            'label_singular' => '',
            'label_plural' => '',
            'collapsible' => FALSE,
            'collapsed' => FALSE,
            'allow_new' => TRUE,
            'allow_existing' => FALSE,
            'match_operator' => 'CONTAINS',
            'allow_duplicate' => FALSE,
          ],
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_label',
          'weight' => $options['weight']['view'] ?? 0,
          'settings' => [
            'link' => TRUE,
          ],
        ];
        break;

      // Otherwise, throw an exception.
      default:
        throw new FieldException('Unsupported target_type.');
    }

    // Set the handler and handler settings.
    $field->setSetting('handler', $handler);
    $field->setSetting('handler_settings', $handler_settings);

    // Set form and view display options.
    $field->setDisplayOptions('form', $form_display_options);
    $field->setDisplayOptions('view', $view_display_options);
  }

  /**
   * Entity reference revisions field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyEntityReferenceRevisionsField(BaseFieldDefinition &$field, array $options = []) {

    // If a target type is not specified, throw an exception.
    if (empty($options['target_type'])) {
      throw new FieldException('No target_type was specified.');
    }

    // Set the target type.
    $field->setSetting('target_type', $options['target_type']);

    // Build additional settings based on the target type.
    switch ($options['target_type']) {

      // Quantity reference.
      case 'quantity':
        $handler = 'default:quantity';
        $handler_settings = [
          'target_bundles' => NULL,
          'sort' => [
            'field' => 'label',
            'direction' => 'asc',
          ],
          'auto_create' => FALSE,
          'auto_create_bundle' => '',
        ];
        $form_display_options = [
          'type' => 'inline_entity_form_complex',
          'settings' => [
            'form_mode' => 'default',
            'revision' => TRUE,
            'override_labels' => FALSE,
            'label_singular' => '',
            'label_plural' => '',
            'collapsible' => FALSE,
            'collapsed' => FALSE,
            'allow_new' => TRUE,
            'allow_existing' => FALSE,
            'match_operator' => 'CONTAINS',
            'allow_duplicate' => FALSE,
          ],
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'label' => 'inline',
          'type' => 'entity_reference_revisions_entity_view',
          'settings' => [
            'view_mode' => 'default',
            'link' => FALSE,
          ],
          'weight' => $options['weight']['view'] ?? 0,
        ];
        break;

      // Otherwise, throw an exception.
      default:
        throw new FieldException('Unsupported target_type.');
    }

    // Set the handler and handler settings.
    $field->setSetting('handler', $handler);
    $field->setSetting('handler_settings', $handler_settings);

    // Set form and view display options.
    $field->setDisplayOptions('form', $form_display_options);
    $field->setDisplayOptions('view', $view_display_options);
  }

  /**
   * File and image field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyFileField(BaseFieldDefinition &$field, array $options = []) {

    // Determine the upload directory.
    $file_directory = 'farm/[date:custom:Y]-[date:custom:m]';
    if (!empty($options['file_directory'])) {
      $file_directory = $options['file_directory'];
    }

    // Set field settings.
    $settings = [
      'file_directory' => $file_directory,
      'max_filesize' => '',
      'handler' => 'default:file',
      'handler_settings' => [],
    ];
    switch ($options['type']) {

      case 'file':
        $settings['file_extensions'] = 'csv doc docx gz geojson gpx kml kmz logz mp3 odp ods odt ogg pdf ppt pptx tar tif tiff txt wav xls xlsx zip';
        $settings['description_field'] = TRUE;
        break;

      case 'image':
        $settings['file_extensions'] = 'png gif jpg jpeg';
        $settings['max_resolution'] = '';
        $settings['min_resolution'] = '';
        $settings['alt_field'] = FALSE;
        $settings['alt_field_required'] = FALSE;
        $settings['title_field'] = FALSE;
        $settings['title_field_required'] = FALSE;
        $settings['default_image'] = [
          'uuid' => '',
          'alt' => '',
          'title' => '',
          'width' => NULL,
          'height' => NULL,
        ];
        break;

    }
    $field->setSettings($settings);

    // Build form and view display settings.
    switch ($options['type']) {

      case 'file':
        $form_display_options = [
          'type' => 'file_generic',
          'settings' => [
            'progress_indicator' => 'throbber',
          ],
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'type' => 'file_table',
          'label' => 'visually_hidden',
          'settings' => [
            'use_description_as_link_text' => TRUE,
          ],
          'weight' => $options['weight']['view'] ?? 0,
        ];
        break;

      case 'image':
        $form_display_options = [
          'type' => 'image_image',
          'settings' => [
            'preview_image_style' => 'medium',
            'progress_indicator' => 'throbber',
          ],
          'weight' => $options['weight']['form'] ?? 0,
        ];
        $view_display_options = [
          'type' => 'image',
          'label' => 'visually_hidden',
          'settings' => [
            'image_style' => 'large',
            'image_link' => 'file',
          ],
          'weight' => $options['weight']['view'] ?? 0,
        ];
        break;
    }
    if (!empty($form_display_options)) {
      $field->setDisplayOptions('form', $form_display_options);
    }
    if (!empty($view_display_options)) {
      $field->setDisplayOptions('view', $view_display_options);
    }
  }

  /**
   * Fraction field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyFractionField(BaseFieldDefinition &$field, array $options = []) {

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'fraction_decimal',
      'weight' => $options['weight']['form'] ?? 0,
      'settings' => [
        'precision' => 0,
        'auto_precision' => TRUE,
      ],
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'fraction_decimal',
      'settings' => [
        'precision' => 0,
        'auto_precision' => TRUE,
        'separator' => '/',
        'prefix_suffix' => FALSE,
      ],
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Geofield field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyGeofieldField(BaseFieldDefinition &$field, array $options = []) {

    // Set the geofield backend.
    $field->setSetting('backend', 'geofield_backend_default');

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'farm_map_geofield',
      'weight' => $options['weight']['form'] ?? 0,
      'settings' => [
        'populate_file_field' => $options['populate_file_field'] ?? FALSE,
      ],
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'farm_map_geofield',
      'settings' => [
        'output_format' => 'wkt',
        'output_escape' => TRUE,
      ],
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * ID tag field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyIdTagField(BaseFieldDefinition &$field, array $options = []) {

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'id_tag',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'id_tag',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Integer field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyIntegerField(BaseFieldDefinition &$field, array $options = []) {

    // Set the size, if specified.
    if (!empty($options['size'])) {
      $field->setSetting('size', $options['size']);
    }

    // Set the min/max constraints, if specified.
    if (isset($options['min'])) {
      $field->setSetting('min', $options['min']);
    }
    if (isset($options['max'])) {
      $field->setSetting('max', $options['max']);
    }

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'number',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'number_integer',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Inventory field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyInventoryField(BaseFieldDefinition &$field, array $options = []) {

    // Build view display settings.
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'inventory',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * List string field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyListStringField(BaseFieldDefinition &$field, array $options = []) {

    // Set the allowed values, if specified.
    if (!empty($options['allowed_values'])) {
      $field->setSetting('allowed_values', $options['allowed_values']);
    }

    // Set the allowed values function, if specified.
    if (!empty($options['allowed_values_function'])) {
      $field->setSetting('allowed_values_function', $options['allowed_values_function']);
    }

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'options_buttons',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'list_default',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * String field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyStringField(BaseFieldDefinition &$field, array $options = []) {

    // Set the maximum length, if specified.
    if (!empty($options['max_length'])) {
      $field->setSetting('max_length', $options['max_length']);
    }

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'settings' => [
        'size' => 60,
        'placeholder' => '',
      ],
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'string',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Text field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyTextField(BaseFieldDefinition &$field, array $options = []) {

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'text_textarea',
      'settings' => [
        'rows' => '5',
        'placeholder' => '',
      ],
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'text_default',
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

  /**
   * Timestamp field modifier.
   *
   * @param \Drupal\Core\Field\BaseFieldDefinition &$field
   *   A base field definition object.
   * @param array $options
   *   An array of options.
   */
  protected function modifyTimestampField(BaseFieldDefinition &$field, array $options = []) {

    // Build form and view display settings.
    $field->setDisplayOptions('form', [
      'type' => 'datetime_timestamp_optional',
      'weight' => $options['weight']['form'] ?? 0,
    ]);
    $field->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'timestamp',
      'settings' => [
        'date_format' => 'medium',
        'custom_date_format' => '',
        'timezone' => '',
      ],
      'weight' => $options['weight']['view'] ?? 0,
    ]);
  }

}
