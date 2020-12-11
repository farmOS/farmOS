<?php

namespace Drupal\plan\Plugin\Plan\PlanType;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base plan type class.
 */
abstract class PlanTypeBase extends PluginBase implements PlanTypeInterface {

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

    // Assets in the plan.
    $options = [
      'type' => 'entity_reference',
      'label' => 'Assets',
      'target_type' => 'asset',
      'multiple' => TRUE,
      'hidden' => TRUE,
    ];
    $fields['asset'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Logs in the plan.
    $options = [
      'type' => 'entity_reference',
      'label' => 'Logs',
      'target_type' => 'log',
      'multiple' => TRUE,
      'hidden' => TRUE,
    ];
    $fields['log'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    return $fields;
  }

}
