<?php

namespace Drupal\farm_entity_views;

/**
 * Configures the correct view filter for taxonomy_term reference fields.
 *
 * @see EntityViewsData
 * @see \taxonomy_field_views_data_alter()
 */
trait EntityViewsDataTaxonomyFilterTrait {

  /**
   * {@inheritdoc}
   */
  protected function addReverseRelationships(array &$data, array $fields) {
    parent::addReverseRelationships($data, $fields);

    // Configure the taxonomy_term reference field filter.
    // Logic derived form taxonomy_field_views_data_alter().
    foreach ($fields as $field) {
      if ($field->getSettings()['target_type'] !== 'taxonomy_term') {
        continue;
      }
      $table_name = $this->tableMapping->getFieldTableName($field->getName());
      foreach ($data[$table_name] as $table_field_name => $field_data) {
        $field_name = $field->getName();
        if (isset($field_data['filter']) && strpos($table_field_name, $field_name) === 0) {
          $data[$table_name][$table_field_name]['filter']['id'] = 'taxonomy_index_tid';
        }
      }
    }
  }

}
