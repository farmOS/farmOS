<?php
/**
 * @file
 * Code for managing the location of assets with movement logs.
 */

/**
 * Generate markup that describes an asset's current location.
 *
 * @param FarmAsset $asset
 *   The farm asset.
 *
 * @return string
 *   Returns rendered HTML.
 */
function farm_log_movement_asset_location_markup($asset) {

  // Start an output string.
  $output = '<strong>' . t('Location') . ':</strong> ';

  // Get the asset's location.
  $areas = farm_log_movement_asset_location($asset);

  // If locations were found, add links to them.
  if (!empty($areas)) {
    $area_links = array();
    foreach ($areas as $area) {
      if (!empty($area->tid)) {
        $area_links[] = l($area->name, 'taxonomy/term/' . $area->tid);
      }
    }
    $output .= implode(', ', $area_links);
  }

  // Otherwise, none.
  else {
    $output .= 'N/A';
  }

  // Get the asset's most recent movement.
  $log = farm_log_movement_asset_latest_movement($asset);

  // Load the log's movement field, if it exists.
  if (!empty($log->field_farm_movement[LANGUAGE_NONE][0]['value'])) {
    $movement = field_collection_item_load($log->field_farm_movement[LANGUAGE_NONE][0]['value']);
  }

  // If a geofield exists on the movement, display it.
  if (!empty($movement->field_farm_geofield[LANGUAGE_NONE][0]['geom'])) {

    // Build the geofield map and add it to the page content.
    $field_instance = field_info_instance('field_collection_item', 'field_farm_geofield', 'field_farm_movement');
    $geofield = field_view_field('field_collection_item', $movement, 'field_farm_geofield', $field_instance['display']['default']);
    $geofield['#title'] = t('Geometry');
    $output .= drupal_render($geofield);
  }

  // Return the output markup.
  return $output;
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function farm_log_movement_form_farm_asset_form_alter(&$form, &$form_state, $form_id) {

  // Get the farm asset entity from the form.
  $asset = $form['farm_asset']['#value'];

  // Get the asset's current location.
  $areas = farm_log_movement_asset_location($asset);
  $area_names = array();
  if (!empty($areas)) {
    foreach ($areas as $area) {
      if (!empty($area->name)) {

        // Get the area name.
        $name = $area->name;

        // If the area name contains commas, wrap it in quotes.
        if (strpos($area->name, ',') !== FALSE) {
          $name = '"' . $area->name . '"';
        }

        // Add the name to the list.
        $area_names[] = $name;
      }
    }
  }

  // Assemble the list of areas into a string.
  $location = implode(', ', $area_names);

  // Add a field for setting the asset's current location.
  $form['farm_log_movement_asset_location'] = array(
    '#type' => 'textfield',
    '#title' => t('Current location'),
    '#description' => t('Set the current areas(s) that this asset is in. Separate multiple areas with commas. A movement observation log will be created automatically if you change this field.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#default_value' => $location,
  );
  $form['actions']['submit']['#submit'][] = 'farm_log_movement_asset_location_submit';
}

/**
 * Submit handler for processing the asset location field.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_log_movement_asset_location_submit(array $form, array &$form_state) {

  // Only proceed if farm_log_movement_asset_location has a value.
  if (empty($form_state['values']['farm_log_movement_asset_location'])) {
    return;
  }

  // Only proceed if the value is not the default value.
  if ($form_state['values']['farm_log_movement_asset_location'] == $form['farm_log_movement_asset_location']['#default_value']) {
    return;
  }

  // If an asset doesn't exist, bail.
  if (empty($form_state['values']['farm_asset'])) {
    return;
  }

  // Grab the asset.
  $asset = $form_state['values']['farm_asset'];

  // Explode the value into an array and only take the first value.
  // (Same behavior as taxonomy autocomplete widget.)
  $values = drupal_explode_tags($form_state['values']['farm_log_movement_asset_location']);

  // If the value is empty, bail.
  if (empty($values)) {
    return;
  }

  // Iterate through the values and built an array of areas.
  $areas = array();
  foreach ($values as $value) {

    // Attempt to look up the area by it's name.
    $terms = taxonomy_get_term_by_name($value, 'farm_areas');
    $area = reset($terms);

    // If an area was not found, create a new one.
    if (empty($area)) {
      $farm_areas = taxonomy_vocabulary_machine_name_load('farm_areas');
      $area = new stdClass();
      $area->name = $value;
      $area->vid = $farm_areas->vid;
      taxonomy_term_save($area);
    }

    // Add to the array of areas.
    $areas[] = $area;
  }

  // Create an observation log to record the movement.
  farm_log_movement_observation($asset, $areas, REQUEST_TIME);
}

/**
 * Find the location of an asset.
 *
 * @param FarmAsset $asset
 *   The farm_asset object to look for.
 * @param int $time
 *   Unix timestamp limiter. Only logs before this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done".
 *   Defaults to TRUE.
 *
 * @return array
 *   Returns an array of areas that the asset is in.
 */
function farm_log_movement_asset_location(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {
  $areas = array();

  // Load the log using our helper function.
  $log = farm_log_movement_asset_latest_movement($asset, $time, $done);

  // If a movement field doesn't exist, bail.
  if (empty($log->field_farm_movement[LANGUAGE_NONE][0]['value'])) {
    return $areas;
  }

  // Load the log's movement field
  $movement = field_collection_item_load($log->field_farm_movement[LANGUAGE_NONE][0]['value']);

  if (!empty($movement->field_farm_move_to[LANGUAGE_NONE])) {
    foreach ($movement->field_farm_move_to[LANGUAGE_NONE] as $area_reference) {
      if (!empty($area_reference['tid'])) {
        $term = taxonomy_term_load($area_reference['tid']);
        if (!empty($term)) {
          $areas[] = $term;
        }
      }
    }
  }
  return $areas;
}

/**
 * Load an asset's latest log that defines a movement.
 *
 * @param FarmAsset $asset
 *   The farm_asset object to look for.
 * @param int $time
 *   Unix timestamp limiter. Only logs before this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done". Defaults to
 *   TRUE.
 *
 * @return Log|bool
 *   Returns a log entity. FALSE if something goes wrong.
 */
function farm_log_movement_asset_latest_movement(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {

  // If the asset doesn't have an ID (for instance if it is new and hasn't been
  // saved yet), bail.
  if (empty($asset->id)) {
    return FALSE;
  }

  // Make a query for loading the latest movement log.
  $query = farm_log_movement_asset_movement_query($asset->id, $time, $done);

  // Execute the query and gather the log id.
  $result = $query->execute();
  $log_id = $result->fetchField();

  // If a log id exists, load and return it.
  if (!empty($log_id)) {
    return log_load($log_id);
  }
  return FALSE;
}

/**
 * Build a query to find the latest log of an asset that define a movement.
 *
 * @param int|string $asset_id
 *   The asset id to search for. This can either be a specific id, or a field
 *   alias string from another query (ie: 'mytable.assetid'). For an example
 *   of field alias string usage, see the Views field handler code in
 *   farm_log_handler_relationship_location::query().
 * @param int $time
 *   Unix timestamp limiter. Onlylogs before this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done". Defaults to
 *   TRUE.
 * @param string $field
 *   If the log id is desired, use "log_id. If the movement field_collection id
 *   is desired, use "movement_id".
 *
 * @return \SelectQuery
 *   Returns a SelectQuery object.
 */
function farm_log_movement_asset_movement_query($asset_id, $time = REQUEST_TIME, $done = TRUE, $field = 'log_id') {

  // Build a query to find an asset's latest log that defines a movement.
  // We use the "ss_" prefix throughout to indicate that this is generally going
  // to be used as a sub-select, and to avoid potential name conflicts when this
  // is used elsewhere (ie: farm_log_handler_relationship_location::query()).
  $query = db_select('log', 'ss_log');

  // Join in asset references and filter to only include logs that reference the
  // specified asset.
  $query->join('field_data_field_farm_asset', 'ss_fdffa', "ss_fdffa.entity_type = 'log' AND ss_fdffa.entity_id = ss_log.id");
  $query->where('ss_fdffa.field_farm_asset_target_id = ' . $asset_id);
  $query->where('ss_fdffa.deleted = 0');

  // Join in movement field collections and the "move to" field data.
  // Filter to only include logs that have a movement with a "to" value.
  $query->join('field_data_field_farm_movement', 'ss_fdffm', "ss_fdffm.entity_type = 'log' AND ss_fdffm.entity_id = ss_log.id");
  $query->where('ss_fdffm.deleted = 0');
  $query->join('field_data_field_farm_move_to', 'ss_fdffmt', "ss_fdffmt.entity_type = 'field_collection_item' AND ss_fdffmt.bundle = 'field_farm_movement' AND ss_fdffmt.entity_id = ss_fdffm.field_farm_movement_value");
  $query->where('ss_fdffmt.field_farm_move_to_tid IS NOT NULL');

  // Order by timestamp and then log id, descending.
  $query->orderBy('ss_log.timestamp', 'DESC');
  $query->orderBy('ss_log.id', 'DESC');

  // We only want the first result.
  $query->range(0, 1);

  // If only "done" movement logs should be included, add a filter.
  if ($done) {
    $query->where('ss_log.done = 1');
  }

  // Ensure $time is a positive integer.
  if (!is_int($time) || $time < 0) {
    $time = REQUEST_TIME;
  }

  // If $time is not zero, limit to only logs before it. This allows the
  // absolute last log to be found by setting $time to zero.
  if ($time !== 0) {
    $query->where('ss_log.timestamp <= ' . $time);
  }

  // Add a field to the query based on the function argument.
  if ($field == 'log_id') {
    $query->addField('ss_log', 'id');
  }
  elseif ($field == 'movement_id') {
    $query->addField('ss_fdffm', 'field_farm_movement_value');
  }

  // Return the query object.
  return $query;
}

/**
 * Implements hook_action_info().
 */
function farm_log_movement_action_info() {
  return array(
    'farm_log_movement_asset_move_action' => array(
      'type' => 'farm_asset',
      'label' => t('Move'),
      'configurable' => FALSE,
      'triggers' => array('any'),
      'aggregate' => TRUE,
    ),
  );
}

/**
 * Action function for farm_log_asset_move_action.
 *
 * Creates a new movement activity log for the specified assets.
 *
 * @param array $assets
 *   An array of asset entities to move.
 * @param array $context
 *   Array with parameters for this action.
 */
function farm_log_movement_asset_move_action(array $assets, $context = array()) {

  // Build a list of asset IDs.
  $ids = array();
  foreach ($assets as $asset) {
    $ids[] = $asset->id;
  }

  // Redirect to a form for adding a new movement log.
  drupal_goto('log/add/farm_movement', array('query' => array('farm_asset' => $ids)));
}

/**
 * Create an observation log for moving assets to areas.
 *
 * @param array|FarmAsset $assets
 *   Array of assets to include in the move.
 * @param array $areas
 *   An array of areas to move to.
 * @param int $timestamp
 *   The timestamp of the move. Defaults to the current time.
 */
function farm_log_movement_observation($assets, $areas, $timestamp = REQUEST_TIME) {

  // If $assets isn't an array, wrap it.
  if (!is_array($assets)) {
    $assets = array($assets);
  }

  // If there are no areas to move to, bail.
  if (empty($areas)) {
    return;
  }

  // Create a new movement log entity.
  $log = entity_create('log', array('type' => 'farm_observation'));

  // Create an entity wrapper for the log.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Set the name of the observation: "Current location: [area(s)]". If there
  // are more than 3 areas, just list the first, and add "(+ X more)".
  $log_name = t('Current location') . ': ';
  $area_names = array();
  foreach ($areas as $area) {
    $area_names[] = $area->name;
  }
  $count_areas = count($area_names);
  if (count($area_names) <= 3) {
    $log_name .= implode($area_names, ', ');
  }
  else {
    $log_name .= $area_names[0] . ' (+' . ($count_areas - 1) . ' ' . t('more') . ')';
  }
  $log_wrapper->name->set($log_name);

  // Iterate through the assets.
  foreach ($assets as $asset) {

    // Add the asset to the asset reference field.
    $log_wrapper->field_farm_asset[] = $asset;
  }

  // Set the date.
  $log_wrapper->timestamp->set($timestamp);

  // Create a new movement field_collection entity.
  $movement = entity_create('field_collection_item', array('field_name' => 'field_farm_movement'));

  // Attach the movement to the log.
  $movement->setHostEntity('log', $log);

  // Set the "to" area(s).
  foreach ($areas as $area) {
    $log_wrapper->field_farm_movement->field_farm_move_to[] = $area;
  }

  // Set the log's done status to TRUE.
  $log_wrapper->done->set(TRUE);

  // Save the log.
  $log_wrapper->save();
}
