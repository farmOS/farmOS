<?php

namespace Drupal\farm_material\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the material asset type.
 *
 * @AssetType(
 *   id = "material",
 *   label = @Translation("Material"),
 * )
 */
class Material extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'material_type' => [
        'type' => 'entity_reference',
        'label' => $this->t('Material type'),
        'description' => $this->t("Enter the type of material."),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'material_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'weight' => [
          'form' => -90,
          'view' => -50,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
