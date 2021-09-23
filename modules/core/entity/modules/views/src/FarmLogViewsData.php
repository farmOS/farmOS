<?php

namespace Drupal\farm_entity_views;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\log\LogViewsData;

/**
 * Provides the views data for the log entity type.
 */
class FarmLogViewsData extends LogViewsData {

  use EntityViewsDataTaxonomyFilterTrait;

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Provide a reverse entity reference relationship from quantities to logs
    // that reference them.
    // Workaround for core issue #2706431.
    // Copied from Entity API module's EntityViewsData, modified to support
    // Entity Reference Revisions field.
    // @todo Patch Entity to support Entity Reference Revisions instead?
    $entity_type_id = $this->entityType->id();
    $base_fields = $this->getEntityFieldManager()->getBaseFieldDefinitions($entity_type_id);
    $entity_reference_fields = array_filter($base_fields, function (BaseFieldDefinition $field) {
      return !$field->isComputed() && $field->getType() == 'entity_reference_revisions';
    });
    $this->addReverseRelationships($data, $entity_reference_fields);

    return $data;
  }

}
