<?php

namespace Drupal\farm_entity\Plugin\Plan\PlanType;

/**
 * Provides a farmOS plan type base class.
 */
class FarmPlanType extends PlanTypeBase {

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
