<?php

namespace Drupal\farm_entity_bundle_fields_test\Plugin\Plan\PlanType;

use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the second test plan type.
 *
 * @PlanType(
 *   id = "second",
 *   label = @Translation("Second"),
 * )
 */
class Second extends FarmPlanType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // Inherit all plan fields.
    $fields = parent::buildFieldDefinitions();

    // Create a field for just this bundle.
    $fields['second_plan_field'] = BundleFieldDefinition::create('boolean')
      ->setLabel($this->t('Test field for second plan type'));

    return $fields;
  }

}
