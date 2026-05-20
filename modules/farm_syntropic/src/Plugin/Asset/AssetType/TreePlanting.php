<?php

declare(strict_types=1);

namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\AssetType;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the tree planting asset type.
 */
#[AssetType(
  id: 'tree_planting',
  label: new TranslatableMarkup('Tree Planting'),
)]
class TreePlanting extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $field_info = [
      'species' => [
        'type' => 'entity_reference',
        'label' => $this->t('Species'),
        'description' => $this->t('Species planted in this batch (e.g., Castanea dentata).'),
        'target_type' => 'taxonomy_term',
        'target_bundle' => 'plant_type',
        'auto_create' => TRUE,
        'required' => TRUE,
        'multiple' => FALSE,
        'weight' => ['form' => -90, 'view' => -90],
      ],
      'variety' => [
        'type' => 'string',
        'label' => $this->t('Variety'),
        'description' => $this->t('Cultivar or variety name for the batch.'),
        'weight' => ['form' => -85, 'view' => -85],
      ],
      'tree_count' => [
        'type' => 'integer',
        'label' => $this->t('Tree count'),
        'description' => $this->t('Declared number of trees in this planting. Update manually if trees die or are removed.'),
        'required' => TRUE,
        'min' => 1,
        'weight' => ['form' => -80, 'view' => -80],
      ],
      'spacing_m' => [
        'type' => 'decimal',
        'label' => $this->t('Spacing (m)'),
        'description' => $this->t('On-center spacing in meters (for row plantings; leave blank for blocks).'),
        'precision' => 6,
        'scale' => 2,
        'min' => 0,
        'weight' => ['form' => -75, 'view' => -75],
      ],
      'planting_date' => [
        'type' => 'timestamp',
        'label' => $this->t('Planting date'),
        'description' => $this->t('Date the batch was installed in the ground.'),
        'weight' => ['form' => -70, 'view' => -70],
      ],
      'source' => [
        'type' => 'string',
        'label' => $this->t('Source'),
        'description' => $this->t('Nursery, seed source, or propagation method for the batch.'),
        'weight' => ['form' => -65, 'view' => -65],
      ],
      'notes' => [
        'type' => 'text_long',
        'label' => $this->t('Notes'),
        'description' => $this->t('Free-form notes about the planting event.'),
        'weight' => ['form' => -10, 'view' => -10],
      ],
      // Open-ended WKT per spec decision — no geometry-type validator.
      'geometry' => [
        'type' => 'geofield',
        'label' => $this->t('Geometry'),
        'description' => $this->t('Row (LineString) or block (Polygon) geometry for this planting.'),
        'weight' => ['form' => 0, 'view' => 0],
      ],
    ];

    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }

    return $fields;
  }

}
