<?php

/**
 * @file
 * Farm group membership relationship handler.
 */

/**
 * Farm group membership relationship handler.
 *
 * Create a relationship between a farm_asset, and it's most recent log that
 * assigns group membership, in order to determine its current group.
 *
 * @see farm_log_handler_relationship_asset
 */
class farm_group_handler_relationship_membership extends farm_log_handler_relationship_asset {

  /**
   * {@inheritdoc}
   */
  protected function build_query($asset_id, $time, $done) {
    return farm_group_asset_membership_query($asset_id, $time, $done, TRUE, 'membership_id');
  }

}
