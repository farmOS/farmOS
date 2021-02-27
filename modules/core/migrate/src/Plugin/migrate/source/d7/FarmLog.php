<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\Core\Site\Settings;
use Drupal\log\Plugin\migrate\source\d7\Log;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;

/**
 * Log source from database.
 *
 * Extends the Log source plugin to include source properties needed for the
 * farmOS migration.
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
    $result = parent::prepareRow($row);
    if (!$result) {
      return FALSE;
    }

    // Prepare movement information.
    $this->prepareMovement($row);

    // Prepare quantity information.
    $this->prepareQuantity($row);

    // Return success.
    return TRUE;
  }

  /**
   * Prepare a log's movement information.
   *
   * @param \Drupal\migrate\Row $row
   *   The row object.
   */
  protected function prepareMovement(Row $row) {
    $id = $row->getSourceProperty('id');

    // Determine if we will allow overwriting "Areas" and "Geometry" fields on
    // the log with "Move to" and "Movement geometry" fields.
    $allow_overwrite = Settings::get('farm_migrate_allow_movement_overwrite', FALSE);

    // By default, logs are not movements.
    $is_movement = FALSE;

    // Get movement field values.
    $movement_values = $this->getFieldValues('log', 'field_farm_movement', $id);

    // If the log has a movement, load the Field Collection.
    if (!empty($movement_values)) {

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
      $query->leftJoin('field_data_field_farm_move_to', 'fdffmt', "fdffmt.entity_id = fci.item_id AND fdffmt.entity_type = 'field_collection_item' AND fdffmt.bundle = 'field_farm_movement' AND fdffmt.deleted = 0");
      $query->addField('fdffmt', 'field_farm_move_to_tid', 'tid');
      $query->condition('fci.item_id', $fcid);
      $result = $query->execute()->fetchCol();
      $movement_areas = FALSE;
      if (!empty($result)) {
        foreach ($result as $col) {
          if (!empty($col)) {
            $movement_areas[] = ['tid' => $col];
          }
        }
      }

      // Query the movement geometry.
      $query = $this->select('field_collection_item', 'fci');
      $query->leftJoin('field_data_field_farm_geofield', 'fdffg', "fdffg.entity_id = fci.item_id AND fdffg.entity_type = 'field_collection_item' AND fdffg.bundle = 'field_farm_movement' AND fdffg.deleted = 0");
      $query->addField('fdffg', 'field_farm_geofield_geom', 'geom');
      $query->condition('fci.item_id', $fcid);
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
        $message = 'Movement has a geometry but no areas (log ' . $id . ').';
        $this->idMap->saveMessage(['id' => $id], $message, MigrationInterface::MESSAGE_WARNING);
      }

      // If the log has area references and movement areas, and they are
      // different, throw an exception or print a warning, depending on whether
      // or not we are allowing overwrites.
      if (!empty($log_areas) && !empty($movement_areas) && $log_areas != $movement_areas) {
        $message = 'Log ' . $id . ' has both area references and movement area references. See https://github.com/farmOS/farmOS/blob/2.x/docs/hosting/migration.md#movement-logs';
        if (!$allow_overwrite) {
          throw new MigrateException($message);
        }
        else {
          $this->idMap->saveMessage(['id' => $id], $message, MigrationInterface::MESSAGE_WARNING);
        }
      }

      // If the log has a geometry and a movement geometry, and they are
      // different, throw an exception or print a warning, depending on whether
      // or not we are allowing overwrites.
      if (!empty($log_geometry[0]['geom']) && !empty($movement_geometry[0]['geom']) && $log_geometry[0]['geom'] != $movement_geometry[0]['geom']) {
        $message = 'Log ' . $id . ' has both a geometry and a movement geometry. See https://github.com/farmOS/farmOS/blob/2.x/docs/hosting/migration.md#movement-logs';
        if (!$allow_overwrite) {
          throw new MigrateException($message);
        }
        else {
          $this->idMap->saveMessage(['id' => $id], $message, MigrationInterface::MESSAGE_WARNING);
        }
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

    // Set the "is_movement" property for use in migrations.
    $row->setSourceProperty('is_movement', $is_movement);
  }

  /**
   * Prepare a log's quantity information.
   *
   * @param \Drupal\migrate\Row $row
   *   The row object.
   */
  protected function prepareQuantity(Row $row) {
    $id = $row->getSourceProperty('id');

    // Get quantity log field value.
    $quantity_values = $this->getFieldvalues('log', 'field_farm_quantity', $id);

    // Iterate through quantity field values to collect field collection IDs.
    $quantity_ids = [];
    foreach ($quantity_values as $quantity_value) {
      if (!empty($quantity_value['value'])) {
        $quantity_ids[] = $quantity_value['value'];
      }
    }

    // Add the quantity IDs to the row for future processing.
    $row->setSourceProperty('log_quantities', $quantity_ids);
  }

}
