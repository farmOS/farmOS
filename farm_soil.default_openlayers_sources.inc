<?php
/**
 * @file
 * farm_soil.default_openlayers_sources.inc
 */

/**
 * Implements hook_default_openlayers_sources().
 */
function farm_soil_default_openlayers_sources() {
  $export = array();

  $ol_source = new stdClass();
  $ol_source->disabled = FALSE; /* Edit this to true to make a default ol_source disabled initially */
  $ol_source->api_version = 1;
  $ol_source->machine_name = 'farm_soil_nrcs_soil_survey';
  $ol_source->name = 'NRCS Soil Survey';
  $ol_source->description = '';
  $ol_source->factory_service = 'openlayers.Source:TileWMS';
  $ol_source->options = array(
    'url' => 'http://sdmdataaccess.nrcs.usda.gov/Spatial/SDM.wms',
    'version' => '1.1.1',
    'wms_layers' => 'MapunitPoly',
  );
  $export['farm_soil_nrcs_soil_survey'] = $ol_source;

  return $export;
}
