<?php
/**
 * @file
 * Feeds importers provided by the farm import module.
 */

/**
 * Implements hook_feeds_importer_default().
 */
function farm_import_feeds_importer_default() {
  $export = array();

  // Generate an importer for each asset type.
  $asset_types = farm_asset_types();
  foreach ($asset_types as $bundle => $type) {

    // Generate importer.
    $importer = farm_import_asset_importer($type);

    // Add common field mappings.
    farm_import_add_importer_fields('farm_asset', $bundle, $importer);

    // Add it to the list.
    $export['farm_asset_' . $bundle] = $importer;
  }

  // Generate an importer for each log type.
  $log_types = log_types();
  foreach ($log_types as $bundle => $type) {

    // Generate importer.
    $importer = farm_import_log_importer($type);

    // Add common field mappings.
    farm_import_add_importer_fields('log', $bundle, $importer);

    // Add it to the list.
    $export['log_' . $bundle] = $importer;
  }

  return $export;
}

/**
 * Helper function for generating an asset importer.
 *
 * @param $asset_type
 *   The asset type entity.
 *
 * @return object
 *   Returns a feeds importer configuration object.
 *
 * @see farm_import_feeds_importer_default()
 */
function farm_import_asset_importer($asset_type) {

  // Start with our common base importer.
  $importer = farm_import_base_importer();

  // Add the necessary information.
  $importer->id = 'farm_asset_' . $asset_type->type;
  $importer->config['name'] = 'Asset: ' . $asset_type->label;
  $importer->config['description'] = 'Import ' . $asset_type->label . ' assets from CSV files.';
  $importer->config['processor']['plugin_key'] = 'FarmAssetProcessor';
  $importer->config['processor']['config']['bundle'] = $asset_type->type;
  $importer->config['processor']['config']['mappings'] = array(
    array(
      'source' => 'Name',
      'target' => 'name',
      'unique' => FALSE,
      'language' => 'und',
    ),
    array(
      'source' => 'Created',
      'target' => 'created',
      'unique' => FALSE,
      'language' => 'und',
    ),
    array(
      'source' => 'Active',
      'target' => 'active',
      'unique' => FALSE,
      'language' => 'und',
    ),
  );

  // Return the importer.
  return $importer;
}

/**
 * Helper function for generating a log importer.
 *
 * @param $log_type
 *   The log type entity.
 *
 * @return object
 *   Returns a feeds importer configuration object.
 *
 * @see farm_import_feeds_importer_default()
 */
function farm_import_log_importer($log_type) {

  // Start with our common base importer.
  $importer = farm_import_base_importer();

  // Add the necessary information.
  $importer->id = 'log_' . $log_type->type;
  $importer->config['name'] = 'Log: ' . $log_type->label;
  $importer->config['description'] = 'Import ' . $log_type->label . ' logs from CSV files.';
  $importer->config['processor']['plugin_key'] = 'LogProcessor';
  $importer->config['processor']['config']['bundle'] = $log_type->type;
  $importer->config['processor']['config']['mappings'] = array(
    array(
      'source' => 'Done',
      'target' => 'done',
      'unique' => FALSE,
      'language' => 'und',
    ),
    array(
      'source' => 'Date',
      'target' => 'timestamp',
      'unique' => FALSE,
      'language' => 'und',
    ),
    array(
      'source' => 'Name',
      'target' => 'name',
      'unique' => FALSE,
      'language' => 'und',
    ),
  );

  // Return the importer.
  return $importer;
}

/**
 * Helper function for building a Feeds importer.
 *
 * @return object
 *   Returns a feeds importer object skeleton.
 */
function farm_import_base_importer() {
  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->config = array(
    'fetcher' => array(
      'plugin_key' => 'FeedsFileFetcher',
      'config' => array(
        'allowed_extensions' => 'txt csv tsv',
        'delete_uploaded_file' => 0,
        'direct' => 0,
        'directory' => 'private://feeds',
        'allowed_schemes' => array(
          'public' => 'public',
          'private' => 'private',
        ),
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ',',
        'encoding' => 'UTF-8',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'config' => array(
        'author' => 0,
        'authorize' => 1,
        'insert_new' => '1',
        'update_existing' => '0',
        'update_non_existent' => 'skip',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'language' => 'und',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  return $feeds_importer;
}

/**
 * Add common field mappings to importers.
 *
 * @param $type
 *   The entity type.
 * @param $bundle
 *   The asset bundle.
 * @param $importer
 *   The importer configuration object that will be altered.
 */
function farm_import_add_importer_fields($type, $bundle, $importer) {

  // If the importer doesn't have a mapping array for some reason, bail.
  if (empty($importer->config['processor']['config']['mappings'])) {
    return;
  }

  // Get field mappings, depending on the type.
  switch ($type) {
    case 'farm_asset':
      $mappings = farm_import_asset_field_mappings();
      break;
    case 'log':
      $mappings = farm_import_log_field_mappings();
      break;
    default:
      $mappings = array();
  }

  // Add fields, if they exist on the bundle.
  foreach ($mappings as $field => $mapping) {
    if (!empty(field_info_instance($type, $field, $bundle))) {
      $importer->config['processor']['config']['mappings'][] = $mapping;
    }
  }

  // Add Quantity field, if it exists. This is a bit more complicated because
  // it is a field collection, so there are two targets.
  if (!empty(field_info_instance($type, 'field_farm_quantity', $bundle))) {
    $importer->config['processor']['config']['mappings'][] = array(
      'source' => 'Quantity value',
      'target' => 'field_farm_quantity:field_farm_quantity_value',
      'unique' => FALSE,
      'language' => 'und',
    );
    $importer->config['processor']['config']['mappings'][] = array(
      'source' => 'Quantity unit',
      'target' => 'field_farm_quantity:field_farm_quantity_units',
      'term_search' => '0',
      'autocreate' => 1,
    );
  }
}

/**
 * Define field mapping that will be added to assets.
 *
 * @return array
 *   Returns an array of feeds importer mapping information, keyed by field
 *   machine names.
 */
function farm_import_asset_field_mappings() {
  return array(
    'field_farm_description' => array(
      'source' => 'Description',
      'target' => 'field_farm_description',
      'format' => 'farm_format',
      'unique' => FALSE,
      'language' => 'und',
    ),
    'field_farm_parent' => array(
      'source' => 'Parent IDs',
      'target' => 'field_farm_parent:etid',
      'unique' => FALSE,
      'language' => 'und',
    ),
  );
}

/**
 * Define field mapping that will be added to logs.
 *
 * @return array
 *   Returns an array of feeds importer mapping information, keyed by field
 *   machine names.
 */
function farm_import_log_field_mappings() {
  return array(
    'field_farm_asset' => array(
      'source' => 'Asset IDs',
      'target' => 'field_farm_asset:etid',
      'unique' => FALSE,
      'language' => 'und',
    ),
    'field_farm_area' => array(
      'source' => 'Area names',
      'target' => 'field_farm_area',
      'term_search' => '0',
      'autocreate' => 1,
      'language' => 'und',
    ),
    'field_farm_notes' => array(
      'source' => 'Notes',
      'target' => 'field_farm_notes',
      'format' => 'farm_format',
      'unique' => FALSE,
      'language' => 'und',
    ),
    'field_farm_log_category' => array(
      'source' => 'Category names',
      'target' => 'field_farm_log_category',
      'unique' => FALSE,
      'language' => 'und',
    ),
  );
}
