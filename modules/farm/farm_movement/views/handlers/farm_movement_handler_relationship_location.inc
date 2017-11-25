<?php

/**
 * @file
 * Farm log location relationship handler.
 */

/**
 * Farm log movement location relationship handler.
 *
 * Create a relationship between a farm_asset, and it's most recent log entity's
 * movement field collection, in order to determine its current location.
 *
 * @see farm_log_handler_relationship_asset
 */
class farm_movement_handler_relationship_location extends farm_log_handler_relationship_asset {

  /**
   * {@inheritdoc}
   */
  protected function build_query($asset_id, $time, $done) {
    return farm_movement_asset_movement_query($asset_id, $time, $done, TRUE, 'movement_id');
  }

}
