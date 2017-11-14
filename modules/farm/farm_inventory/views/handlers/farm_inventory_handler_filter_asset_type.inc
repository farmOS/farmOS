<?php

/**
 * @file
 * Farm inventory asset type filter.
 */

/**
 * Filter class which allows to filter by certain bundles of an entity.
 *
 * This extends the core Views views_handler_filter_entity_bundle class and
 * filters the options to only include asset types that have inventory enabled.
 */
class farm_inventory_handler_filter_asset_type extends views_handler_filter_entity_bundle {

  /**
   * @inheritdoc
   */
  function get_value_options() {
    parent::get_value_options();
    $this->value_options = array_intersect_key($this->value_options, array_flip(farm_inventory_asset_types()));
  }
}
