<?php
/**
 * @file
 * farm_mapknitter.default_openlayers_sources.inc
 */

/**
 * Implements hook_default_openlayers_sources().
 */
function farm_mapknitter_default_openlayers_sources() {
  $export = array();

  // Load the MapKnitter title and URL.
  $url = variable_get('farm_mapknitter_url', '');

  // If either the title or URL are blank, or the URL is not valid, bail.
  if (empty($url) || !valid_url($url, TRUE)) {
    return $export;
  }

  // Generate a MapKnitter source.
  $ol_source = new stdClass();
  $ol_source->disabled = FALSE; /* Edit this to true to make a default ol_source disabled initially */
  $ol_source->api_version = 1;
  $ol_source->machine_name = 'farm_mapknitter_source';
  $ol_source->name = 'Farm MapKnitter Source';
  $ol_source->description = '';
  $ol_source->factory_service = 'openlayers.Source:XYZ';
  $ol_source->options = array(
    'url' => $url,
  );
  $export['farm_mapknitter_source'] = $ol_source;

  return $export;
}
