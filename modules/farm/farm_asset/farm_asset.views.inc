<?php

/**
 * @file
 * Farm asset views hooks.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_asset_views_data_alter(&$data) {

  /**
   * What we want to do here is override the reverse entity reference
   * relationship handler that's used to join in farm assets that reference
   * terms. This is used in various farmOS Views that display a list of
   * terms, with a count of the number of assets that are in them.
   *
   * For example: the list of Animal Groups in Farm Livestock, or the list of
   * Crops in Farm Plantings.
   *
   * The reason we override is so that we can add a little extra logic to the
   * join so that only "active" assets are included. This is provided as an
   * option in the relationship handler, so it can be added only when necessary.
   */
  if (!empty($data['taxonomy_term_data'])) {
    foreach ($data['taxonomy_term_data'] as $name => &$definition) {

      // If the name starts with "reverse_" and ends with "_farm_asset",
      // replace the relationship handler with our own.
      $start = 'reverse_';
      $end = '_farm_asset';
      $start_len = strlen($start);
      $end_len = strlen($end);
      if (substr($name, 0, $start_len) == $start && substr($name, ($end_len * -1), $end_len) == $end) {

        // Replace the relationship handler.
        if (!empty($definition['relationship']['handler'])) {
          $definition['relationship']['handler'] = 'farm_asset_handler_relationship_entity_reverse';
        }
      }
    }
  }

  // Add a special filter for asset 'archived' property that treats it as a
  // boolean value.
  if (!empty($data['farm_asset']['archived'])) {
    $data['farm_asset']['archived_boolean'] = array(
      'title' => t('Archived (boolean)'),
      'help' => t('Whether or not the asset is archived.'),
      'filter' => array(
        'real field' => 'archived',
        'handler' => 'views_handler_filter_boolean_operator',
        'type' => 'yes-no',
        'accept null' => TRUE,
      ),
    );
  }
}

/**
 * Implements hook_views_plugins().
 */
function farm_asset_views_plugins() {
  return array(
    'argument validator' => array(
      'farm_asset' => array(
        'title' => t('Farm asset'),
        'handler' => 'farm_asset_plugin_argument_validate_farm_asset',
        'path' => drupal_get_path('module', 'farm_asset') . '/includes/views/plugins',
      ),
    ),
  );
}
