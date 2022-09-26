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

      // If this is not a taxonomy term reference field, skip it.
      if ($field->getSettings()['target_type'] !== 'taxonomy_term') {
        continue;
      }

      // Get the field name.
      $field_name = $field->getName();

      // Iterate through the Views data tables and columns.
      foreach ($data as $table_name => $table_data) {
        foreach ($table_data as $table_field_name => $field_data) {

          // If this field doesn't have a filter handler, skip it.
          if (!isset($field_data['filter'])) {
            continue;
          }

          // Ensure that we are only altering the Views field we want.
          // This will either be the field name itself, or the field name plus
          // a `_target_id` suffix (depending on whether the field is a base or
          // bundle field, single or multiple values, etc).
          $table_field_names = [
            $field_name,
            $field_name . '_target_id',
          ];
          if (in_array($table_field_name, $table_field_names)) {

            // Set the filter handler ID.
            $data[$table_name][$table_field_name]['filter']['id'] = 'taxonomy_index_tid';
          }
        }
      }
    }
  }

}
