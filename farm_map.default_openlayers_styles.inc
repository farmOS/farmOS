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

  // Define the farm styles.
  $styles = array(
    'farm_blue' => array(
      'name' => 'Farm Blue',
      'color' => 'rgba(51,153,255,0.4)',
    ),
  );

  // Iterate through the defined styles and create them.
  foreach ($styles as $machine_name => $style) {
    $ol_style = new stdClass();
    $ol_style->disabled = FALSE; /* Edit this to true to make a default ol_style disabled initially */
    $ol_style->api_version = 1;
    $ol_style->machine_name = $machine_name;
    $ol_style->name = t($style['name']);
    $ol_style->description = '';
    $ol_style->factory_service = 'openlayers.Style:InlineJS';
    $ol_style->options = array(
      'javascript' => 'var fill = new ol.style.Fill({
  color: \'' . $style['color'] . '\'
});
var stroke = new ol.style.Stroke({
  color: \'' . $style['color'] . '\',
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
    $export[$machine_name] = $ol_style;
  }

  return $export;
}
