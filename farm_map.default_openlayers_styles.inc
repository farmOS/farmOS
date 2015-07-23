<?php
/**
 * @file
 * farm_map.default_openlayers_styles.inc
 */

/**
 * Implements hook_default_openlayers_styles().
 */
function farm_map_default_openlayers_styles() {
  $export = array();

  $ol_style = new stdClass();
  $ol_style->disabled = FALSE; /* Edit this to true to make a default ol_style disabled initially */
  $ol_style->api_version = 1;
  $ol_style->machine_name = 'farm_blue';
  $ol_style->name = 'Farm Blue';
  $ol_style->description = '';
  $ol_style->factory_service = 'openlayers.Style:InlineJS';
  $ol_style->options = array(
    'javascript' => 'var fill = new ol.style.Fill({
  color: \'rgba(51,153,255,0.4)\'
});
var stroke = new ol.style.Stroke({
  color: \'rgba(51,153,255,0.7)\',
  width: 2
});
var style = [
  new ol.style.Style({
    image: new ol.style.Circle({
      fill: fill,
      stroke: stroke,
      radius: 6
    }),
    fill: fill,
    stroke: stroke
  })
];',
  );
  $export['farm_blue'] = $ol_style;

  return $export;
}
