<?php

namespace Drupal\farm_log\Plugin\Log\LogType;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base log type class.
 */
abstract class LogTypeBase extends PluginBase implements LogTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getWorkflowId() {
    return $this->pluginDefinition['workflow'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];

    // Geometry field.
    // This is added as a bundle field definition to all log types rather than
    // a base field definition so that data is stored in a dedicated database
    // table.
    $options = [
      'type' => 'geofield',
      'label' => 'Geometry',
      'description' => 'Add geometry data to this log to describe where it took place.',
      'weight' => [
        'form' => 95,
        'view' => 95,
      ],
    ];
    $fields['geometry'] = farm_field_bundle_field_definition($options);

    return $fields;
  }

}
