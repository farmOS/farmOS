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

  // Load the MapKnitter slug.
  $slug = variable_get('farm_mapknitter_slug', '');

  // If the slug is blank, bail.
  if (empty($slug)) {
    return $export;
  }

  // Check plain.
  $slug = check_plain($slug);

  // Assemble the URL.
  $url = 'http://mapknitter.org/tms/' . $slug . '/{z}/{x}/{-y}.png';

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
