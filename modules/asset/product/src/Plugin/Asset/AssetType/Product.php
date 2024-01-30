<?php

namespace Drupal\farm_product\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the product asset type.
 *
 * @AssetType(
 *   id = "product",
 *   label = @Translation("Product"),
 * )
 */
class Product extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'product_type' => [
        'type' => 'entity_reference',
        'label' => $this->t('Product type'),
        'description' => $this->t("Enter the type of product."),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'product_type',
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
