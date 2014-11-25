<?php
/**
 * @file
 * farm_soil.openlayers_maps.inc
 */

/**
 * Implements hook_openlayers_maps().
 */
function farm_soil_openlayers_maps() {
  $export = array();

  $openlayers_maps = new stdClass();
  $openlayers_maps->disabled = FALSE; /* Edit this to true to make a default openlayers_maps disabled initially */
  $openlayers_maps->api_version = 1;
  $openlayers_maps->name = 'farm_soil_map';
  $openlayers_maps->title = 'Farm Soil Map';
  $openlayers_maps->description = 'Map of NRCS Soil Survey data.';
  $openlayers_maps->data = array(
    'width' => 'auto',
    'height' => '400px',
    'image_path' => drupal_get_path('module', 'openlayers') . '/themes/default_dark/img/',
    'css_path' => drupal_get_path('module', 'openlayers') . '/themes/default_dark/style.css',
    'proxy_host' => '',
    'hide_empty_map' => array(),
    'center' => array(
      'initial' => array(
        'centerpoint' => '-75.14, 39.95',
        'zoom' => '12',
      ),
      'restrict' => array(
        'restrictextent' => 0,
        'restrictedExtent' => '',
      ),
    ),
    'behaviors' => array(
      'openlayers_behavior_fullscreen' => array(
        'activated' => 0,
      ),
      'openlayers_behavior_keyboarddefaults' => array(),
      'openlayers_behavior_layerswitcher' => array(
        'ascending' => 1,
        'sortBaseLayer' => '0',
        'roundedCorner' => 1,
        'roundedCornerColor' => '#222222',
        'maximizeDefault' => 0,
        'div' => '',
      ),
      'openlayers_behavior_dragpan' => array(),
      'openlayers_behavior_navigation' => array(
        'zoomWheelEnabled' => 1,
        'zoomBoxEnabled' => 1,
        'documentDrag' => 0,
      ),
      'openlayers_behavior_panzoombar' => array(
        'zoomWorldIcon' => 0,
        'panIcons' => 1,
      ),
      'openlayers_behavior_scaleline' => array(),
      'openlayers_behavior_zoomtolayer' => array(
        'zoomtolayer' => array(
          'geofield_formatter' => 'geofield_formatter',
          'google_satellite' => 0,
          'google_hybrid' => 0,
          'google_normal' => 0,
          'google_physical' => 0,
          'nrcs' => 0,
        ),
        'point_zoom_level' => '16',
        'zoomtolayer_scale' => '1',
      ),
    ),
    'default_layer' => 'google_satellite',
    'layers' => array(
      'google_satellite' => 'google_satellite',
      'google_hybrid' => 'google_hybrid',
      'google_normal' => 'google_normal',
      'google_physical' => 'google_physical',
      'geofield_formatter' => 'geofield_formatter',
      'farm_soil_nrcs' => 'farm_soil_nrcs',
    ),
    'layer_weight' => array(
      'nrcs' => '0',
      'geofield_formatter' => '0',
      'openlayers_geojson_picture_this' => '0',
      'openlayers_kml_example' => '0',
      'farm_soil_nrcs' => '0',
    ),
    'layer_styles' => array(
      'farm_soil_nrcs' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'geofield_formatter' => 'farm_blue',
      'nrcs' => '0',
    ),
    'layer_styles_select' => array(
      'farm_soil_nrcs' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'geofield_formatter' => 'farm_orange',
      'nrcs' => '0',
    ),
    'layer_styles_temporary' => array(
      'farm_soil_nrcs' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'geofield_formatter' => 'farm_green',
      'nrcs' => '0',
    ),
    'layer_activated' => array(
      'farm_soil_nrcs' => 'farm_soil_nrcs',
      'geofield_formatter' => 'geofield_formatter',
      'nrcs' => 0,
      'openlayers_kml_example' => 0,
      'openlayers_geojson_picture_this' => 0,
    ),
    'layer_switcher' => array(
      'geofield_formatter' => 'geofield_formatter',
      'farm_soil_nrcs' => 0,
      'nrcs' => 0,
      'openlayers_kml_example' => 0,
      'openlayers_geojson_picture_this' => 0,
    ),
    'projection' => 'EPSG:3857',
    'displayProjection' => 'EPSG:4326',
    'styles' => array(
      'default' => 'farm_blue',
      'select' => 'farm_orange',
      'temporary' => 'farm_green',
    ),
  );
  $export['farm_soil_map'] = $openlayers_maps;

  return $export;
}
