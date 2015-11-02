<?php
/**
 * @file
 * farm_fields.features.filter.inc
 */

/**
 * Implements hook_filter_default_formats().
 */
function farm_fields_filter_default_formats() {
  $formats = array();

  // Exported format: Farm Format.
  $formats['farm_format'] = array(
    'format' => 'farm_format',
    'name' => 'Farm Format',
    'cache' => 1,
    'status' => 1,
    'weight' => -10,
    'filters' => array(
      'filter_autop' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_url' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(
          'filter_url_length' => 72,
        ),
      ),
    ),
  );

  return $formats;
}
