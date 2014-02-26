<?php
/**
 * @file
 * farm_map.openlayers_styles.inc
 */

/**
 * Implements hook_openlayers_styles().
 */
function farm_map_openlayers_styles() {
  $export = array();

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_blue';
  $openlayers_styles->title = 'Farm Blue';
  $openlayers_styles->description = 'Blue farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#09F',
    'strokeColor' => '#06F',
    'strokeWidth' => 4,
    'fillOpacity' => 0.5,
    'strokeOpacity' => 0.7,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_blue'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_green';
  $openlayers_styles->title = 'Farm Green';
  $openlayers_styles->description = 'Green farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#090',
    'strokeColor' => '#060',
    'strokeWidth' => 4,
    'fillOpacity' => 0.5,
    'strokeOpacity' => 0.7,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_green'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_orange';
  $openlayers_styles->title = 'Farm Orange';
  $openlayers_styles->description = 'Orange farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#F90',
    'strokeColor' => '#F60',
    'strokeWidth' => 4,
    'fillOpacity' => 0.5,
    'strokeOpacity' => 0.7,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_orange'] = $openlayers_styles;

  return $export;
}
