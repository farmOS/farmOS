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
      'log_filter' => array(
        'weight' => -49,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_html' => array(
        'weight' => -48,
        'status' => 1,
        'settings' => array(
          'allowed_html' => '<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd>',
          'filter_html_help' => 1,
          'filter_html_nofollow' => 0,
        ),
      ),
      'filter_autop' => array(
        'weight' => -46,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_url' => array(
        'weight' => -45,
        'status' => 1,
        'settings' => array(
          'filter_url_length' => 72,
        ),
      ),
    ),
  );

  return $formats;
}
