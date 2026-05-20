<?php

declare(strict_types=1);

namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\AssetType;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the tree asset type.
 */
#[AssetType(
  id: 'tree',
  label: new TranslatableMarkup('Tree'),
)]
class Tree extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Species'),
        'description' => $this->t('Tree species (e.g., Castanea dentata).'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'plant_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'multiple' => FALSE,
        'weight' => [
          'form' => -90,
          'view' => -90,
        ],
      ],
      'variety' => [
        'type' => 'string',
        'label' => $this->t('Variety'),
        'description' => $this->t('Cultivar or variety name.'),
        'weight' => [
          'form' => -85,
          'view' => -85,
        ],
      ],
      // Bounds chosen to catch operator typos and bad sensor data without
      // rejecting legitimate values:
      // - DBH 0-1000 cm: covers everything from a seedling (~0.1) up to the
      //   largest known trees on earth (~970 cm for General Sherman).
      // - Height 0-200 m: covers everything from a seedling up to the
      //   tallest known coastal redwood (~115 m), with headroom.
      // - Canopy radius 0-100 m: extreme upper bound for a single tree's
      //   spread; in practice almost always well below 30 m.
      // Field factory translates min/max to FieldItem settings, which
      // Drupal then enforces in the form widget AND on entity validation
      // (so JSON:API writes are also gated).
      'dbh_cm' => [
        'type' => 'decimal',
        'label' => $this->t('DBH (cm)'),
        'description' => $this->t('Diameter at breast height in centimeters.'),
        'precision' => 6,
        'scale' => 2,
        'min' => 0,
        'max' => 1000,
        'weight' => [
          'form' => -40,
          'view' => -40,
        ],
      ],
      'height_m' => [
        'type' => 'decimal',
        'label' => $this->t('Height (m)'),
        'description' => $this->t('Tree height in meters.'),
        'precision' => 6,
        'scale' => 2,
        'min' => 0,
        'max' => 200,
        'weight' => [
          'form' => -35,
          'view' => -35,
        ],
      ],
      'canopy_radius_m' => [
        'type' => 'decimal',
        'label' => $this->t('Canopy radius (m)'),
        'description' => $this->t('Canopy radius in meters.'),
        'precision' => 6,
        'scale' => 2,
        'min' => 0,
        'max' => 100,
        'weight' => [
          'form' => -30,
          'view' => -30,
        ],
      ],
      'stratum' => [
        'type' => 'entity_reference',
        'label' => $this->t('Stratum'),
        'description' => $this->t('Syntropic functional layer assignment.'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'syntropic_stratum',
        'auto_create' => TRUE,
        'weight' => [
          'form' => -25,
          'view' => -25,
        ],
      ],
      'succession_stage' => [
        'type' => 'entity_reference',
        'label' => $this->t('Succession stage'),
        'description' => $this->t('Current succession phase.'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'succession_stage',
        'auto_create' => TRUE,
        'weight' => [
          'form' => -20,
          'view' => -20,
        ],
      ],
      'health_status' => [
        'type' => 'entity_reference',
        'label' => $this->t('Health status'),
        'description' => $this->t('Current health assessment.'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'tree_health',
        'auto_create' => TRUE,
        'weight' => [
          'form' => -15,
          'view' => -15,
        ],
      ],
      'rootstock' => [
        'type' => 'string',
        'label' => $this->t('Rootstock'),
        'description' => $this->t('Rootstock variety if grafted.'),
        'weight' => [
          'form' => -10,
          'view' => -10,
        ],
      ],
      'graft_variety' => [
        'type' => 'string',
        'label' => $this->t('Graft variety'),
        'description' => $this->t('Scion variety if grafted.'),
        'weight' => [
          'form' => -8,
          'view' => -8,
        ],
      ],
      'planting_date' => [
        'type' => 'timestamp',
        'label' => $this->t('Planting date'),
        'description' => $this->t('Date planted in ground.'),
        'weight' => [
          'form' => -75,
          'view' => -75,
        ],
      ],
      'source' => [
        'type' => 'string',
        'label' => $this->t('Source'),
        'description' => $this->t('Origin (nursery name, seed source, cutting).'),
        'weight' => [
          'form' => -70,
          'view' => -70,
        ],
      ],
      'odoo_lot' => [
        'type' => 'string',
        'label' => $this->t('Odoo lot/serial'),
        'description' => $this->t('Odoo lot or serial number for inventory traceability.'),
        'weight' => [
          'form' => -5,
          'view' => -5,
        ],
      ],
      // Parent planting: typed reference to a tree_planting asset.
      // FarmFieldFactory::modifyEntityReferenceField() case 'asset' (lines
      // 354-367 of FarmFieldFactory.php) honors 'target_bundle' for asset
      // targets the same way it does for taxonomy_term targets. No
      // config/install override is needed.
      'parent_planting' => [
        'type' => 'entity_reference',
        'label' => $this->t('Tree Planting'),
        'description' => $this->t('The grouped planting this tree belongs to.'),
        'target_type' => 'asset',
        'target_bundle' => 'tree_planting',
        'multiple' => FALSE,
        'weight' => ['form' => -80, 'view' => -80],
      ],
    ];

    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }

    return $fields;
  }

}
