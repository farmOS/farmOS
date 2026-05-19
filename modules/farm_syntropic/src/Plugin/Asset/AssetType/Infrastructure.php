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
   * Field-info options for the structured capacity fields.
   *
   * Returned as a 2-element array keyed by field name so both this plugin's
   * buildFieldDefinitions() AND the farm_syntropic_update_8001() migration
   * hook can build the field definitions from a single source of truth.
   *
   * Uses TranslatableMarkup directly (not the $this->t() helper) so the
   * method remains static — the install hook calls it without
   * instantiating the plugin.
   *
   * @return array<string, array<string, mixed>>
   *   Keyed by field name ('capacity_value', 'capacity_unit'). Values are
   *   options arrays compatible with FarmFieldFactory::bundleFieldDefinition().
   */
  public static function capacityFieldOptions(): array {
    return [
      'capacity_value' => [
        'type' => 'decimal',
        'label' => new TranslatableMarkup('Capacity value'),
        'description' => new TranslatableMarkup('Numeric magnitude (e.g. 400 for a 400 W panel).'),
        // DECIMAL(10,3) is intentionally wider than Tree decimal fields'
        // DECIMAL(6,2) — covers cistern volumes up to 9,999,999 gallons
        // and sub-GPM flow rates without rounding loss.
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
        'label' => new TranslatableMarkup('Capacity unit'),
        'description' => new TranslatableMarkup('Unit of measure for the capacity value.'),
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
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Pull capacity field options from the shared source-of-truth method so
    // a fresh install and a drush-updb migration always produce the same
    // schema. See capacityFieldOptions() above.
    $capacity = self::capacityFieldOptions();

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
      'capacity_value' => $capacity['capacity_value'],
      'capacity_unit' => $capacity['capacity_unit'],
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
