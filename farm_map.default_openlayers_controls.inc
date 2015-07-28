<?php
/**
 * @file
 * farm_map.default_openlayers_controls.inc
 */

/**
 * Implements hook_default_openlayers_controls().
 */
function farm_map_default_openlayers_controls() {
  $export = array();

  $ol_control = new stdClass();
  $ol_control->disabled = FALSE; /* Edit this to true to make a default ol_control disabled initially */
  $ol_control->api_version = 1;
  $ol_control->machine_name = 'farm_map_control_layer_switcher';
  $ol_control->name = 'Farm Map: Layer Switcher';
  $ol_control->description = '';
  $ol_control->factory_service = 'openlayers.Control:OL3LayerSwitcher';
  $ol_control->options = array(
    'collapsible' => 0,
  );
  $export['farm_map_control_layer_switcher'] = $ol_control;

  return $export;
}
