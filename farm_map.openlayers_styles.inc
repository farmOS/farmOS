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
    'fillColor' => '#39F',
    'fillOpacity' => 0.5,
    'strokeColor' => '#39F',
    'strokeWidth' => 2,
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
    'fillColor' => '#963',
    'fillOpacity' => 0.5,
    'strokeColor' => '#963',
    'strokeWidth' => 2,
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
    'fillColor' => '#393',
    'fillOpacity' => 0.5,
    'strokeColor' => '#393',
    'strokeWidth' => 2,
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
    'fillColor' => '#F93',
    'fillOpacity' => 0.5,
    'strokeColor' => '#F93',
    'strokeWidth' => 2,
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
    'fillColor' => '#C36',
    'fillOpacity' => 0.5,
    'strokeColor' => '#C36',
    'strokeWidth' => 2,
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
  $openlayers_styles->name = 'farm_red';
  $openlayers_styles->title = 'Farm Red';
  $openlayers_styles->description = 'Red farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#C00',
    'fillOpacity' => 0.5,
    'strokeColor' => '#C00',
    'strokeWidth' => 2,
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_red'] = $openlayers_styles;

  $openlayers_styles = new stdClass();
  $openlayers_styles->disabled = FALSE; /* Edit this to true to make a default openlayers_styles disabled initially */
  $openlayers_styles->api_version = 1;
  $openlayers_styles->name = 'farm_yellow';
  $openlayers_styles->title = 'Farm Yellow';
  $openlayers_styles->description = 'Yellow farm style';
  $openlayers_styles->data = array(
    'pointRadius' => 6,
    'fillColor' => '#FF3',
    'fillOpacity' => 0.5,
    'strokeColor' => '#FF3',
    'strokeWidth' => 2,
    'strokeOpacity' => 0.69999999999999996,
    'strokeLinecap' => 'round',
    'strokeDashstyle' => 'solid',
    'graphicOpacity' => 1,
    'labelAlign' => 'cm',
  );
  $export['farm_yellow'] = $openlayers_styles;

  return $export;
}
