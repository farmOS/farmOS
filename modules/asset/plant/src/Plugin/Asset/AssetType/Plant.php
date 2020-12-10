<?php

namespace Drupal\farm_plant\Plugin\Asset\AssetType;

use Drupal\asset\Plugin\Asset\AssetType\AssetTypeBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides the plant asset type.
 *
 * @AssetType(
 *   id = "plant",
 *   label = @Translation("Plant"),
 * )
 */
class Plant extends AssetTypeBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
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
      $fields[$name] = farm_field_base_field_definition($info);
    }
    return $fields;
  }

}
