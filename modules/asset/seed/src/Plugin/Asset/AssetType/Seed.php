<?php

namespace Drupal\farm_seed\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the seed asset type.
 *
 * @AssetType(
 *   id = "seed",
 *   label = @Translation("Seed"),
 * )
 */
class Seed extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'plant_type' => [
        'type' => 'entity_reference',
        'label' => $this->t('Crop/variety'),
        'description' => "Enter this seed asset's crop/variety.",
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'plant_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'multiple' => TRUE,
        'weight' => [
          'form' => -90,
          'view' => -90,
        ],
      ],
      'season' => [
        'type' => 'entity_reference',
        'label' => $this->t('Season'),
        'description' => $this->t('Assign this to a season for easier searching later.'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'season',
        'auto_create' => TRUE,
        'multiple' => TRUE,
        'weight' => [
          'form' => -50,
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
