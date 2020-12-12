<?php

namespace Drupal\farm_field\Plugin\Log\LogType;

use Drupal\farm_log\Plugin\Log\LogType\LogTypeBase;

/**
 * Provides a farmOS log type base class.
 */
class FarmLogType extends LogTypeBase {

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
    $fields['geometry'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Add an Equipment reference field if the Equipment module is enabled.
    if (\Drupal::moduleHandler()->moduleExists('farm_equipment')) {
      $options = [
        'type' => 'entity_reference',
        'label' => t('Equipment used'),
        'description' => t('What equipment was used?'),
        'target_type' => 'asset',
        'multiple' => TRUE,
        'weight' => [
          'form' => 55,
          'view' => -5,
        ],
      ];
      $field = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
      $field->setSetting('handler', 'default:asset');
      $field->setSetting('handler_settings', [
        'target_bundles' => [
          'equipment' => 'equipment',
        ],
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => FALSE,
        'auto_create_bundle' => '',
      ]);
      $fields['equipment'] = $field;
    }

    return $fields;
  }

}
