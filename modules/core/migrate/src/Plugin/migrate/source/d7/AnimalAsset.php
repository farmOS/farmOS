<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\migrate\Row;

/**
 * Animal asset source from database.
 *
 * @MigrateSource(
 *   id = "d7_animal_asset",
 *   source_module = "farm_asset"
 * )
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 *
 * @phpstan-ignore-next-line
 */
class AnimalAsset extends FarmAsset {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $id = $row->getSourceProperty('id');

    // Get animal tag field values.
    $animal_tag_values = $this->getFieldValues('farm_asset', 'field_farm_animal_tag', $id);

    // Iterate through tag field values to collect field collection item IDs.
    $field_collection_item_ids = [];
    foreach ($animal_tag_values as $animal_tag_value) {
      if (!empty($animal_tag_value['value'])) {
        $field_collection_item_ids[] = $animal_tag_value['value'];
      }
    }

    // Iterate through the field collection IDs and load values.
    $animal_tags = [];
    foreach ($field_collection_item_ids as $item_id) {

      // Query the animal tag information from the field collection.
      $query = $this->select('field_collection_item', 'fci')
        ->condition('fci.item_id', $item_id)
        ->condition('fci.field_name', 'field_farm_animal_tag');

      // Join the tag ID field.
      $query->leftJoin('field_data_field_farm_animal_tag_id', 'fdffati', 'fdffati.entity_id = fci.item_id AND fdffati.deleted = 0');
      $query->addField('fdffati', 'field_farm_animal_tag_id_value', 'id');

      // Join the tag type field.
      $query->leftJoin('field_data_field_farm_animal_tag_type', 'fdffatt', 'fdffatt.entity_id = fci.item_id AND fdffatt.deleted = 0');
      $query->addField('fdffatt', 'field_farm_animal_tag_type_value', 'type');

      // Join the tag location field.
      $query->leftJoin('field_data_field_farm_animal_tag_location', 'fdffatl', 'fdffatl.entity_id = fci.item_id AND fdffatl.deleted = 0');
      $query->addField('fdffatl', 'field_farm_animal_tag_location_value', 'location');

      // Execute the query.
      $animal_tags[] = $query->execute()->fetchAssoc();
    }

    // Add the ID tags to the row for future processing.
    $row->setSourceProperty('animal_tags', $animal_tags);

    return parent::prepareRow($row);
  }

}
