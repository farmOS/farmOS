<?php

declare(strict_types=1);

namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\AssetType;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the infrastructure asset type.
 */
#[AssetType(
  id: 'infrastructure',
  label: new TranslatableMarkup('Infrastructure'),
)]
class Infrastructure extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'infrastructure_type' => [
        'type' => 'entity_reference',
        'label' => $this->t('Infrastructure type'),
        'description' => $this->t('The type of infrastructure (solar, irrigation, fence, etc.).'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'infrastructure_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'weight' => [
          'form' => -90,
          'view' => -90,
        ],
      ],
      'material' => [
        'type' => 'string',
        'label' => $this->t('Material'),
        'description' => $this->t('Construction material.'),
        'weight' => [
          'form' => -50,
          'view' => -50,
        ],
      ],
      'capacity_value' => [
        'type' => 'decimal',
        'label' => $this->t('Capacity value'),
        'description' => $this->t('Numeric magnitude (e.g. 400 for a 400 W panel).'),
        'precision' => 10,
        'scale' => 3,
        'min' => 0,
        'weight' => [
          'form' => -47,
          'view' => -47,
        ],
      ],
      'capacity_unit' => [
        'type' => 'list_string',
        'label' => $this->t('Capacity unit'),
        'description' => $this->t('Unit of measure for the capacity value.'),
        'allowed_values' => [
          'watts' => 'Watts (W)',
          'kilowatts' => 'Kilowatts (kW)',
          'gpm' => 'Gallons per minute (GPM)',
          'lpm' => 'Litres per minute (LPM)',
          'gallons' => 'Gallons (gal)',
          'litres' => 'Litres (L)',
          'amps' => 'Amps (A)',
          'volts' => 'Volts (V)',
          'linear_feet' => 'Linear feet (ft)',
          'linear_meters' => 'Linear meters (m)',
        ],
        'weight' => [
          'form' => -46,
          'view' => -46,
        ],
      ],
      'installation_date' => [
        'type' => 'timestamp',
        'label' => $this->t('Installation date'),
        'description' => $this->t('Date installed.'),
        'weight' => [
          'form' => -80,
          'view' => -80,
        ],
      ],
      'condition' => [
        'type' => 'list_string',
        'label' => $this->t('Condition'),
        'description' => $this->t('Current physical condition.'),
        'allowed_values' => [
          'new' => 'New',
          'good' => 'Good',
          'fair' => 'Fair',
          'needs_repair' => 'Needs repair',
          'decommissioned' => 'Decommissioned',
        ],
        'weight' => [
          'form' => -40,
          'view' => -40,
        ],
      ],
      'specifications' => [
        'type' => 'text_long',
        'label' => $this->t('Specifications'),
        'description' => $this->t('Technical details and notes.'),
        'weight' => [
          'form' => -10,
          'view' => -10,
        ],
      ],
    ];

    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }

    return $fields;
  }

}
