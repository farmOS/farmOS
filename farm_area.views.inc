<?php

/**
 * @file
 * Farm Area Views data.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_area_views_data_alter(&$data) {

  // Add a field for farm area details.
  $data['taxonomy_term_data']['farm_area_details'] = array(
    'field' => array(
      'title' => t('Area Details'),
      'help' => t('Details that are relevant to farm areas.'),
      'handler' => 'farm_area_handler_field_details',
    ),
  );

  // Add a field for farm area links.
  $data['taxonomy_term_data']['farm_area_links'] = array(
    'field' => array(
      'title' => t('Area Links'),
      'help' => t('Links that are relevant to farm areas.'),
      'handler' => 'farm_area_handler_field_links',
    ),
  );
}
