<?php

namespace Drupal\farm_animal\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the animal asset type.
 *
 * @AssetType(
 *   id = "animal",
 *   label = @Translation("Animal"),
 * )
 */
class Animal extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'animal_type' => [
        'type' => 'entity_reference',
        'label' => $this->t('Species/breed'),
        'description' => $this->t("Enter this animal asset's species/breed."),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'animal_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'weight' => [
          'form' => -90,
          'view' => -50,
        ],
      ],
      'birthdate' => [
        'type' => 'timestamp',
        'label' => $this->t('Birthdate'),
        'weight' => [
          'form' => 15,
          'view' => -35,
        ],
      ],
      'is_castrated' => [
        'type' => 'boolean',
        'label' => $this->t('Castrated'),
        'description' => $this->t('Has this animal been castrated?'),
        'weight' => [
          'form' => 26,
        ],
        'view_display_options' => [
          'label' => 'inline',
          'type' => 'hideable_boolean',
          'settings' => [
            'format' => 'default',
            'format_custom_false' => '',
            'format_custom_true' => '',
            'hide_if_false' => TRUE,
          ],
          'weight' => -25,
        ],
      ],
      'nickname' => [
        'type' => 'string',
        'label' => $this->t('Nicknames'),
        'description' => $this->t('List any nicknames of this animal.'),
        'multiple' => TRUE,
        'weight' => [
          'form' => 10,
          'view' => -40,
        ],
      ],
      'sex' => [
        'type' => 'list_string',
        'label' => $this->t('Sex'),
        'allowed_values' => [
          'F' => $this->t('Female'),
          'M' => $this->t('Male'),
        ],
        'weight' => [
          'form' => 20,
          'view' => -30,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
