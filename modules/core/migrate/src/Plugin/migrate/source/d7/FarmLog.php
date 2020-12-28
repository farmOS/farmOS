<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\Core\Site\Settings;
use Drupal\log\Plugin\migrate\source\d7\Log;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Row;

/**
 * Log source from database.
 *
 * @MigrateSource(
 *   id = "d7_farm_log",
 *   source_module = "log"
 * )
 */
class FarmLog extends Log {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $id = $row->getSourceProperty('id');

    // Determine if we will allow overwriting "Areas" and "Geometry" fields on
    // the log with "Move to" and "Movement geometry" fields.
    $allow_overwrite = Settings::get('farm_migrate_allow_movement_overwrite', FALSE);

    // By default, logs are not movements.
    $is_movement = FALSE;

    // Get movement field value.
    $movement_value = $this->getFieldValues('log', 'field_farm_movement', $id);

    // If the log has a movement, load the Field Collection.
    if (!empty($movement_value)) {

      // Get movement field collection values.
      $movement_values = $this->getFieldValues('log', 'field_farm_movement', $id);

      // Iterate through movement field values to collect field collection item
      // IDs.
      $field_collection_item_ids = [];
      foreach ($movement_values as $movement_value) {
        if (!empty($movement_value['value'])) {
          $field_collection_item_ids[] = $movement_value['value'];
        }
      }

      // There should only be one movement field collection associated with a
      // log, so take the first.
      $fcid = reset($field_collection_item_ids);

      // Query the movement area references.
      $query = $this->select('field_collection_item', 'fci');
      $query->leftJoin('field_data_field_farm_move_to', 'fdffmt', 'fdffmt.entity_id = fci.item_id AND fdffmt.deleted = 0');
      $query->addField('fdffmt', 'field_farm_move_to_tid', 'tid');
      $query->condition('fci.item_id', $fcid);
      $query->condition('fci.field_name', 'field_farm_movement');
      $result = $query->execute()->fetchCol();
      $movement_areas = FALSE;
      if (!empty($result)) {
        foreach ($result as $col) {
          $movement_areas[] = ['tid' => $col];
        }
      }

      // Query the movement geometry.
      $query = $this->select('field_collection_item', 'fci');
      $query->leftJoin('field_data_field_farm_geofield', 'fdffg', 'fdffg.entity_id = fci.item_id AND fdffg.deleted = 0');
      $query->addField('fdffg', 'field_farm_geofield_geom', 'geom');
      $query->condition('fci.item_id', $fcid);
      $query->condition('fci.field_name', 'field_farm_movement');
      $result = $query->execute()->fetchField();
      $movement_geometry = FALSE;
      if (!empty($result)) {
        $movement_geometry = [['geom' => $result]];
      }

      // Get any areas/geometry stored on the log itself.
      $log_areas = $this->getFieldValues('log', 'field_farm_area', $id);
      $log_geometry = $this->getFieldValues('log', 'field_farm_geofield', $id);

      // If the log has movement areas, then the log is a movement.
      if (!empty($movement_areas)) {
        $is_movement = TRUE;
      }

      // If the log has a movement geometry, but no movement areas, throw an
      // exception.
      if (empty($movement_areas) && !empty($movement_geometry)) {
        throw new MigrateException('Movement has a geometry but no areas (log ' . $id . ').');
      }

      // If we are not allowing overwriting, the log has area references and
      // movement areas, and they are different, throw an exception.
      if (!$allow_overwrite && !empty($log_areas) && !empty($movement_areas) && $log_areas != $movement_areas) {
        throw new MigrateException('Log ' . $id . ' has both area references and movement area references.');
      }

      // If we are not allowing overwriting, the log has a geometry and a
      // movement geometry, and they are different, throw an exception.
      if (!$allow_overwrite && !empty($log_geometry[0]['geom']) && !empty($movement_geometry[0]['geom']) && $log_geometry[0]['geom'] != $movement_geometry[0]['geom']) {
        throw new MigrateException('Log ' . $id .  ' has both a geometry and a movement geometry.');
      }

      // If the log has movement areas, copy them to the log itself.
      // This will overwrite existing area references, but an exception should
      // be thrown above unless overwriting is explicitly allowed.
      if (!empty($movement_areas)) {
        $row->setSourceProperty('field_farm_area', $movement_areas);
      }

      // If the log has a movement geometry, copy it to the log itself.
      // This will overwrite an existing geometry, but an exception should
      // be thrown above unless overwriting is explicitly allowed.
      if (!empty($movement_geometry)) {
        $row->setSourceProperty('field_farm_geofield', $movement_geometry);
      }
    }

    // Set the "movement" property for use in migrations.
    $row->setSourceProperty('movement', $is_movement);

    return parent::prepareRow($row);
  }

}
