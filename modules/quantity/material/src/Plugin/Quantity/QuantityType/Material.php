<?php

namespace Drupal\farm_quantity_material\Plugin\Quantity\QuantityType;

use Drupal\farm_entity\Plugin\Quantity\QuantityType\FarmQuantityType;

/**
 * Provides the material quantity type.
 *
 * @QuantityType(
 *   id = "material",
 *   label = @Translation("Material"),
 * )
 */
class Material extends FarmQuantityType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // Inherit default quantity fields.
    $fields = parent::buildFieldDefinitions();

    // Material type.
    $options = [
      'type' => 'entity_reference',
      'label' => $this->t('Material type'),
      'description' => $this->t('What type of materials are being applied?'),
      'target_type' => 'taxonomy_term',
      'target_bundle' => 'material_type',
      'auto_create' => TRUE,
      'multiple' => TRUE,
      'weight' => [
        'form' => -50,
        'view' => -50,
      ],
    ];
    $fields['material_type'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
