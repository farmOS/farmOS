<?php
/**
 * @file
 * Openlayers for farm_soil.
 */

/**
 * Implements hook_openlayers_layers().
 */
function farm_soil_openlayers_layers() {
  $export = array();

  $openlayers_layers = new stdClass();
  $openlayers_layers->disabled = FALSE; /* Edit this to true to make a default openlayers_layers disabled initially */
  $openlayers_layers->api_version = 1;
  $openlayers_layers->name = 'farm_soil_nrcs';
  $openlayers_layers->title = 'NRCS Soil Survey';
  $openlayers_layers->description = 'NRCS Soil Survey layer.';
  $openlayers_layers->data = array(
    'base_url' => 'http://sdmdataaccess.nrcs.usda.gov/Spatial/SDM.wms',
    'params' => array(
      'buffer' => '2',
      'ratio' => '1.5',
      'singleTile' => 0,
    ),
    'options' => array(
      'TRANSPARENT' => 'true',
      'exceptions' => 'application/vnd.ogc.se_inimage',
      'format' => 'image/png',
      'layers' => array(
        0 => 'MapunitPoly',
      ),
      'styles' => '',
    ),
    'projection' => array(
      0 => 'EPSG:3857',
      1 => 'EPSG:4326',
    ),
    'isBaseLayer' => 0,
    'layer_type' => 'openlayers_layer_type_wms',
    'layer_handler' => 'wms',
    'transitionEffect' => 'resize',
    'weight' => 0,
    'serverResolutions' => array(
      0 => 156543.03390000001,
      1 => 78271.516950000005,
      2 => 39135.758475000002,
      3 => 19567.879237500001,
      4 => 9783.9396187500006,
      5 => 4891.9698093750003,
      6 => 2445.9849046875001,
      7 => 1222.9924523437501,
      8 => 611.49622617187504,
      9 => 305.74811308593752,
      10 => 152.87405654296876,
      11 => 76.43702827148438,
      12 => 38.21851413574219,
      13 => 19.109257067871095,
      14 => 9.5546285339355475,
      15 => 4.7773142669677737,
      16 => 2.3886571334838869,
      17 => 1.1943285667419434,
      18 => 0.59716428337097172,
      19 => 0.29858214169740677,
      20 => 0.14929107084870338,
      21 => 0.074645535424351692,
    ),
    'resolutions' => array(
      0 => 156543.03390000001,
      1 => 78271.516950000005,
      2 => 39135.758475000002,
      3 => 19567.879237500001,
      4 => 9783.9396187500006,
      5 => 4891.9698093750003,
      6 => 2445.9849046875001,
      7 => 1222.9924523437501,
      8 => 611.49622617187504,
      9 => 305.74811308593752,
      10 => 152.87405654296876,
      11 => 76.43702827148438,
      12 => 38.21851413574219,
      13 => 19.109257067871095,
      14 => 9.5546285339355475,
      15 => 4.7773142669677737,
      16 => 2.3886571334838869,
      17 => 1.1943285667419434,
      18 => 0.59716428337097172,
      19 => 0.29858214169740677,
      20 => 0.14929107084870338,
      21 => 0.074645535424351692,
    ),
  );
  $export['farm_soil_nrcs'] = $openlayers_layers;

  return $export;
}
