<?php
/**
 * @file
 * farm_area.openlayers_maps.inc
 */

/**
 * Implements hook_openlayers_maps().
 */
function farm_area_openlayers_maps() {
  $export = array();

  $openlayers_maps = new stdClass();
  $openlayers_maps->disabled = FALSE; /* Edit this to true to make a default openlayers_maps disabled initially */
  $openlayers_maps->api_version = 1;
  $openlayers_maps->name = 'farm_map_areas';
  $openlayers_maps->title = 'Farm Areas Map';
  $openlayers_maps->description = 'Map of farm areas';
  $openlayers_maps->data = array(
    'width' => 'auto',
    'height' => '400px',
    'image_path' => 'profiles/root/modules/contrib/openlayers/themes/default_dark/img/',
    'css_path' => 'profiles/root/modules/contrib/openlayers/themes/default_dark/style.css',
    'proxy_host' => '',
    'hide_empty_map' => 0,
    'center' => array(
      'initial' => array(
        'centerpoint' => '-95.99999999999764, 40.00000000000016',
        'zoom' => '4',
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
      'openlayers_behavior_geofield' => array(),
      'openlayers_behavior_navigation' => array(
        'zoomWheelEnabled' => 1,
        'zoomBoxEnabled' => 1,
        'documentDrag' => 0,
      ),
      'openlayers_behavior_panzoombar' => array(
        'zoomWorldIcon' => 0,
        'panIcons' => 1,
      ),
      'openlayers_behavior_popup' => array(
        'layers' => array(
          'farm_areas_openlayers_1' => 'farm_areas_openlayers_1',
        ),
        'panMapIfOutOfView' => 1,
        'keepInMap' => 1,
      ),
      'openlayers_behavior_scaleline' => array(),
      'openlayers_behavior_zoomtolayer' => array(
        'zoomtolayer' => array(
          'farm_areas_openlayers_1' => 'farm_areas_openlayers_1',
          'google_satellite' => 0,
        ),
        'point_zoom_level' => '16',
        'zoomtolayer_scale' => '1',
      ),
    ),
    'default_layer' => 'google_satellite',
    'layers' => array(
      'google_satellite' => 'google_satellite',
      'farm_areas_openlayers_1' => 'farm_areas_openlayers_1',
    ),
    'layer_weight' => array(
      'farm_areas_openlayers_1' => '0',
      'openlayers_geojson_picture_this' => '0',
      'openlayers_kml_example' => '0',
      'geofield_formatter' => '0',
    ),
    'layer_styles' => array(
      'geofield_formatter' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => 'farm_blue',
    ),
    'layer_styles_select' => array(
      'geofield_formatter' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => 'farm_orange',
    ),
    'layer_styles_temporary' => array(
      'geofield_formatter' => '0',
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => 'farm_green',
    ),
    'layer_activated' => array(
      'farm_areas_openlayers_1' => 'farm_areas_openlayers_1',
      'geofield_formatter' => 0,
      'openlayers_kml_example' => 0,
      'openlayers_geojson_picture_this' => 0,
    ),
    'layer_switcher' => array(
      'farm_areas_openlayers_1' => 0,
      'geofield_formatter' => 0,
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
  $export['farm_map_areas'] = $openlayers_maps;

  $openlayers_maps = new stdClass();
  $openlayers_maps->disabled = FALSE; /* Edit this to true to make a default openlayers_maps disabled initially */
  $openlayers_maps->api_version = 1;
  $openlayers_maps->name = 'farm_map_geofield';
  $openlayers_maps->title = 'Farm Geofield Map';
  $openlayers_maps->description = 'Farm map of Geofield data.';
  $openlayers_maps->data = array(
    'width' => 'auto',
    'height' => '400px',
    'image_path' => 'profiles/root/modules/contrib/openlayers/themes/default_dark/img/',
    'css_path' => 'profiles/root/modules/contrib/openlayers/themes/default_dark/style.css',
    'proxy_host' => '',
    'hide_empty_map' => 0,
    'center' => array(
      'initial' => array(
        'centerpoint' => '-95.99999999999764, 40.00000000000016',
        'zoom' => '4',
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
      'openlayers_behavior_geofield' => array(),
      'openlayers_behavior_navigation' => array(
        'zoomWheelEnabled' => 1,
        'zoomBoxEnabled' => 1,
        'documentDrag' => 0,
      ),
      'openlayers_behavior_panzoombar' => array(
        'zoomWorldIcon' => 0,
        'panIcons' => 1,
      ),
      'openlayers_behavior_popup' => array(
        'layers' => array(
          'geofield_formatter' => 'geofield_formatter',
        ),
        'panMapIfOutOfView' => 1,
        'keepInMap' => 1,
      ),
      'openlayers_behavior_scaleline' => array(),
      'openlayers_behavior_zoomtolayer' => array(
        'zoomtolayer' => array(
          'geofield_formatter' => 'geofield_formatter',
          'google_satellite' => 0,
        ),
        'point_zoom_level' => '16',
        'zoomtolayer_scale' => '1',
      ),
    ),
    'default_layer' => 'google_satellite',
    'layers' => array(
      'google_satellite' => 'google_satellite',
      'geofield_formatter' => 'geofield_formatter',
    ),
    'layer_weight' => array(
      'geofield_formatter' => '0',
      'farm_areas_openlayers_1' => '0',
      'openlayers_geojson_picture_this' => '0',
      'openlayers_kml_example' => '0',
    ),
    'layer_styles' => array(
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => '0',
      'geofield_formatter' => 'farm_blue',
    ),
    'layer_styles_select' => array(
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => '0',
      'geofield_formatter' => 'farm_orange',
    ),
    'layer_styles_temporary' => array(
      'openlayers_kml_example' => '0',
      'openlayers_geojson_picture_this' => '0',
      'farm_areas_openlayers_1' => '0',
      'geofield_formatter' => 'farm_green',
    ),
    'layer_activated' => array(
      'geofield_formatter' => 'geofield_formatter',
      'openlayers_kml_example' => 0,
      'openlayers_geojson_picture_this' => 0,
      'farm_areas_openlayers_1' => 0,
    ),
    'layer_switcher' => array(
      'geofield_formatter' => 'geofield_formatter',
      'openlayers_kml_example' => 0,
      'openlayers_geojson_picture_this' => 0,
      'farm_areas_openlayers_1' => 0,
    ),
    'projection' => 'EPSG:3857',
    'displayProjection' => 'EPSG:4326',
    'styles' => array(
      'default' => 'farm_blue',
      'select' => 'farm_orange',
      'temporary' => 'farm_green',
    ),
  );
  $export['farm_map_geofield'] = $openlayers_maps;

  return $export;
}
