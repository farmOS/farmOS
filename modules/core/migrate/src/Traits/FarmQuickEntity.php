<?php

namespace Drupal\farm_migrate\Traits;

use Drupal\migrate\Row;

/**
 * Asset source from database.
 */
trait FarmQuickEntity {

  /**
   * Prepare reference to the 1.x quick form that created this entity.
   *
   * @param \Drupal\migrate\Row $row
   *   The row object.
   * @param string $entity_type
   *   The 2.x entity type.
   */
  public function prepareQuickEntityRow(Row $row, string $entity_type) {
    $quick = [];

    // Load the entity ID.
    $id = $row->getSourceProperty('id');

    // Translate the 2.x entity type names to 1.x.
    $old_entity_types = [
      'asset' => 'farm_asset',
      'log' => 'log',
      'plan' => 'farm_plan',
    ];
    if (array_key_exists($entity_type, $old_entity_types)) {
      $entity_type = $old_entity_types[$entity_type];
    }

    // Check to see if the 1.x farm_quick module is enabled.
    $query = $this->select('system', 's');
    $query->condition('s.name', 'farm_quick');
    $query->condition('s.status', 1);
    $enabled = $query->countQuery()->execute()->fetchField();

    // If the farm_quick module is enabled, look up quick form(s) that are
    // linked to this entity.
    if (!empty($enabled)) {
      $query = $this->select('farm_quick_entity', 'fqe');
      $query->condition('entity_type', $entity_type);
      $query->condition('entity_id', $id);
      $query->addField('fqe', 'quick_form_id');
      $result = $query->execute()->fetchCol();
      if (!empty($result)) {
        foreach ($result as $col) {
          if (!empty($col)) {
            $quick[] = $col;
          }
        }
      }
    }

    // Set the "quick" source property.
    $row->setSourceProperty('quick', $quick);
  }

}
