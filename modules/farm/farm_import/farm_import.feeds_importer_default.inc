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
  foreach ($asset_types as $type) {
    $export['farm_asset_' . $type->type] = farm_import_asset_importer($type);
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
  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'farm_asset_' . $asset_type->type;
  $feeds_importer->config = array(
    'name' => 'Farm Asset: ' . $asset_type->label,
    'description' => 'Import ' . $asset_type->label . ' assets from CSV files.',
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
      'plugin_key' => 'FarmAssetProcessor',
      'config' => array(
        'author' => 0,
        'authorize' => 1,
        'mappings' => array(
          array(
            'source' => 'Asset ID',
            'target' => 'id',
            'unique' => 1,
            'language' => 'und',
          ),
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
          array(
            'source' => 'Description',
            'target' => 'field_farm_description',
            'format' => 'farm_format',
            'language' => 'und',
          ),
          array(
            'source' => 'Parent IDs',
            'target' => 'field_farm_parent:etid',
            'unique' => FALSE,
            'language' => 'und',
          ),
        ),
        'insert_new' => '1',
        'update_existing' => '2',
        'update_non_existent' => 'skip',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => $asset_type->type,
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
