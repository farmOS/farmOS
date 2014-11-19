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
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_blue'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_brown';
  $openlayers_styles->title = 'Farm Brown';
  $openlayers_styles->description = 'Brown farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#930',
    'fillOpacity' => 0.5,
    'strokeColor' => '#630',
    'strokeWidth' => 4,
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_brown'] = $openlayers_styles;

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
    'strokeOpacity' => 0.69999999999999996,
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
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_orange'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_purple';
  $openlayers_styles->title = 'Farm Purple';
  $openlayers_styles->description = 'Purple farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#903',
    'fillOpacity' => 0.5,
    'strokeColor' => '#603',
    'strokeWidth' => 4,
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_purple'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_yellow';
  $openlayers_styles->title = 'Farm Yellow';
  $openlayers_styles->description = 'Yellow farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#FF0',
    'fillOpacity' => 0.5,
    'strokeColor' => '#FC0',
    'strokeWidth' => 4,
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_yellow'] = $openlayers_styles;

  return $export;
}
