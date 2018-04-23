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
function farm_movement_asset_location_markup($asset) {

  // Start an output string.
  $output = '<strong>' . t('Location') . ':</strong> ';

  // Get the asset's location.
  $areas = farm_movement_asset_location($asset);

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
  $log = farm_movement_asset_latest_movement($asset);

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
function farm_movement_form_farm_asset_form_alter(&$form, &$form_state, $form_id) {

  // Get the farm asset entity from the form.
  $asset = $form['farm_asset']['#value'];

  // Get the asset's current location.
  $areas = farm_movement_asset_location($asset);
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
  $form['location'] = array(
    '#type' => 'fieldset',
    '#title' => t('Location'),
    '#description' => t('Set the current areas(s) that this asset is in. Separate multiple areas with commas. A movement observation log will be created automatically if you change this field.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#weight' => 100,
    '#tree' => TRUE,
  );
  $form['location']['areas'] = array(
    '#type' => 'textfield',
    '#title' => t('Current location'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#default_value' => $location,
  );
  $form['actions']['submit']['#submit'][] = 'farm_movement_asset_location_submit';
  $form['#group_children']['location'] = 'group_farm_general';
}

/**
 * Submit handler for processing the asset location field.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_movement_asset_location_submit(array $form, array &$form_state) {

  // Only proceed if current location field has a value.
  if (empty($form_state['values']['location']['areas'])) {
    return;
  }

  // Only proceed if the value is not the default value.
  if ($form_state['values']['location']['areas'] == $form['location']['areas']['#default_value']) {
    return;
  }

  // If an asset doesn't exist, bail.
  if (empty($form_state['values']['farm_asset'])) {
    return;
  }

  // Grab the asset.
  $asset = $form_state['values']['farm_asset'];

  // Load the areas.
  $areas = farm_movement_parse_areas($form_state['values']['location']['areas']);

  // Create an observation log to record the movement.
  farm_movement_create($asset, $areas, REQUEST_TIME);
}

/**
 * Parse a string of area names and return an array of loaded area entities.
 *
 * @param string $area_names
 *   A comma-separated list of area names.
 *
 * @return array
 *   Returns an array of loaded area entities.
 */
function farm_movement_parse_areas($area_names) {

  // Start with an empty array.
  $areas = array();

  // Explode the value into an array and only take the first value.
  // (Same behavior as taxonomy autocomplete widget.)
  $values = drupal_explode_tags($area_names);

  // If the value is empty, bail.
  if (empty($values)) {
    return $areas;
  }

  // Iterate through the values and built an array of areas.
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

  // Return the array of areas.
  return $areas;
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
function farm_movement_asset_location(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {
  $areas = array();

  // Load the log using our helper function.
  $log = farm_movement_asset_latest_movement($asset, $time, $done);

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
 * Retrieve an area's movement history. This will provide an array of arrival
 * and departure logs for each asset that has been moved to the area. Only
 * movement logs that have been marked "done" will be included in the history.
 *
 * @param $area
 *   The farm area (taxonomy term object).
 * @param string|array $asset_types
 *   Limit to only include certain asset types. This can be a single asset type
 *   as a string, or an array of asset types. Defaults to empty array, which
 *   will include all asset types.
 * @param int $start_time
 *   How far back to look? This should be a UNIX timestamp. Defaults to NULL,
 *   which looks back through all movement logs in the system.
 * @param int $end_time
 *   How far forward to look? This should be a UNIX timestamp. Defaults to the
 *   current time, which causes future arrival movements to be excluded.
 *
 * @return array
 *   Returns an array of movement history for each asset in the area. Array
 *   keys are asset IDs, and each asset will contain an array of arrays that
 *   contain arrival and departure logs for each movement through the area.
 *   If an asset moved through the area more than once, it will have multiple
 *   sub-arrays for each arrival+departure. If a departure log is not found
 *   (eg: if the asset has not left the area), the 'depart' key will be NULL.
 *
 *   Example:
 *     array(
 *       '50' => array(
 *         array(
 *           'arrive' => [$log],
 *           'depart' => [$log],
 *         ),
 *         array(
 *           'arrive' => [$log],
 *           'depart' => NULL,
 *         ),
 *       ),
 *       '51' => array(
 *         array(
 *           'arrive' => [$log],
 *           'depart' => [$log],
 *         ),
 *       ),
 *     );
 */
function farm_movement_area_history($area, $asset_types = array(), $start_time = NULL, $end_time = REQUEST_TIME) {

  // Start an empty history array.
  $history = array();

  // If the area doesn't have an id, bail.
  if (empty($area->tid)) {
    return $history;
  }

  // If $asset_types is not an array, wrap it in one.
  if (!is_array($asset_types)) {
    $asset_types = array($asset_types);
  }

  // Build a query to retrieve movement logs to this area.
  $query = farm_movement_area_movement_query($area->tid, $end_time);

  // Add the log ID field.
  $query->addField('ss_log', 'id');

  // Filter to only include logs that happened AFTER the start time.
  if (!empty($start_time)) {
    $query->condition('ss_log.timestamp', $start_time, '>');
  }

  // Join in asset references, and then the farm_asset table record for each.
  $query->join('field_data_field_farm_asset', 'ss_fdffa', "ss_fdffa.entity_type = 'log' AND ss_fdffa.entity_id = ss_log.id AND ss_fdffa.deleted = 0");
  $query->join('farm_asset', 'ss_fa', 'ss_fa.id = ss_fdffa.field_farm_asset_target_id');

  // Filter to only include certain asset types.
  if (!empty($asset_types)) {
    $query->condition('ss_fa.type', $asset_types, 'IN');
  }

  // Group by log ID so that we don't get duplicate rows from logs that
  // reference multiple assets.
  $query->groupBy('ss_log.id');

  // Execute the query to get a list of log IDs.
  $result = $query->execute();

  // Iterate through the log IDs.
  foreach ($result as $row) {

    // If the log ID is empty, skip it.
    if (empty($row->id)) {
      continue;
    }

    // Load the asset's arrival log.
    $log_arrive = log_load($row->id);

    // Create an entity metadata wrapper for the log.
    $log_wrapper = entity_metadata_wrapper('log', $log_arrive);

    // Iterate through the assets.
    foreach ($log_wrapper->field_farm_asset as $asset_wrapper) {

      // Get the asset object.
      $asset = $asset_wrapper->value();

      // The the asset doesn't have an ID, skip it.
      if (empty($asset->id)) {
        continue;
      }

      // If the asset is not one of the desired types, skip it.
      if (!empty($asset_types) && !in_array($asset->type, $asset_types)) {
        continue;
      }

      // Look up the asset's next movement log (departure from the area). Only
      // include logs that have been marked "done".
      $log_depart = farm_movement_asset_next_movement($asset, $log_arrive->timestamp, TRUE);

      // Record the asset's time spent in this area.
      $history[$asset->id][] = array(
        'arrive' => $log_arrive,
        'depart' => !empty($log_depart) ? $log_depart : NULL,
      );
    }
  }

  // Return the history.
  return $history;
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
function farm_movement_asset_latest_movement(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {

  /**
   * Please read the comments in farm_movement_asset_movement_query() to
   * understand how this works, and to be aware of the limitations and
   * responsibilities we have in this function with regard to sanitizing query
   * inputs.
   */

  // If the asset doesn't have an ID (for instance if it is new and hasn't been
  // saved yet), bail.
  if (empty($asset->id)) {
    return FALSE;
  }

  // Make a query for loading the latest movement log.
  $query = farm_movement_asset_movement_query($asset->id, $time, $done);

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
 * Load an asset's next log that defines a movement.
 *
 * @param FarmAsset $asset
 *   The farm_asset object to look for.
 * @param int $time
 *   Unix timestamp limiter. Only logs after this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute first.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done". Defaults to
 *   FALSE because the default $time is now, and future logs are generally not
 *   done yet.
 *
 * @return Log|bool
 *   Returns a log entity. FALSE if something goes wrong.
 */
function farm_movement_asset_next_movement(FarmAsset $asset, $time = REQUEST_TIME, $done = FALSE) {

  /**
   * Please read the comments in farm_movement_asset_movement_query() to
   * understand how this works, and to be aware of the limitations and
   * responsibilities we have in this function with regard to sanitizing query
   * inputs.
   */

  // Ensure $time is valid, because it may be used directly in the query
  // string. This is defensive code. See note about
  // farm_movement_asset_movement_query() above.
  if (!is_numeric($time) || $time < 0) {
    $time = REQUEST_TIME;
  }

  // If the asset doesn't have an ID (for instance if it is new and hasn't been
  // saved yet), bail.
  if (empty($asset->id)) {
    return FALSE;
  }

  // Make a query to load all movement logs for the asset. Use a timestamp of 0
  // to include future logs.
  $query = farm_movement_asset_movement_query($asset->id, 0, $done, FALSE);

  // Filter to only include movements after the specified timestamp.
  $query->where('ss_log.timestamp > ' . $time);

  // Order by timestamp and log ID ascending so we can get the first one (this
  // overrides the default sort added by farm_log_query())
  $query->orderBy('ss_log.timestamp', 'ASC');
  $query->orderBy('ss_log.id', 'ASC');

  // Limit to 1 record.
  $query->range(0, 1);

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
 * Build a query to find movement logs of a specific asset.
 *
 * @param int|string $asset_id
 *   The asset id to search for. This can either be a specific id, or a field
 *   alias string from another query (ie: 'mytable.assetid'). For an example
 *   of field alias string usage, see the Views relationship handler code in
 *   farm_movement_handler_relationship_location::query().
 * @param int $time
 *   Unix timestamp limiter. Only logs before this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done". Defaults to
 *   TRUE.
 * @param bool $single
 *   Whether or not to limit the query to a single result. Defaults to TRUE.
 * @param string $field
 *   If the log id is desired, use "log_id. If the movement field_collection id
 *   is desired, use "movement_id".
 *
 * @return \SelectQuery
 *   Returns a SelectQuery object.
 */
function farm_movement_asset_movement_query($asset_id, $time = REQUEST_TIME, $done = TRUE, $single = TRUE, $field = 'log_id') {

  /**
   * Please read the comments in farm_log_asset_query() to understand how this
   * works, and to be aware of the limitations and responsibilities we have in
   * this function with regard to sanitizing query inputs.
   */

  // Use the farm_log_asset_query() helper function to start a query object.
  $query = farm_log_asset_query($asset_id, $time, $done, $single);

  // Join in the Movement field collection and filter to only include logs with
  // movements. Use an inner join to exclude logs that do not have any
  // movement field collections attached.
  $query->innerJoin('field_data_field_farm_movement', 'ss_fdffm', "ss_fdffm.entity_type = 'log' AND ss_fdffm.entity_id = ss_log.id AND ss_fdffm.deleted = 0");

  // Join in the movement's "move to" field, and filter to only include logs
  // that have a movement with a "move to" value. Use an inner join to exclude
  // logs that do not have a "move to" area reference.
  $query->innerJoin('field_data_field_farm_move_to', 'ss_fdffmt', "ss_fdffmt.entity_type = 'field_collection_item' AND ss_fdffmt.bundle = 'field_farm_movement' AND ss_fdffmt.entity_id = ss_fdffm.field_farm_movement_value AND ss_fdffmt.deleted = 0");

  // If $field is 'log_id', then add the log ID field.
  if ($field == 'log_id') {
    $query->addField('ss_log', 'id');
  }

  // Or, if $field is 'movement_id', then add the movement ID field.
  elseif ($field == 'movement_id') {
    $query->addField('ss_fdffm', 'field_farm_movement_value');
  }

  // Return the query object.
  return $query;
}

/**
 * Build a query to find movement logs to a specific area.
 *
 * @param int $area_id
 *   The area id to search for.
 * @param int $time
 *   Unix timestamp limiter. Only logs before this time will be included.
 *   Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show logs that are marked as "done". Defaults to
 *   TRUE.
 *
 * @return \SelectQuery
 *   Returns a SelectQuery object.
 */
function farm_movement_area_movement_query($area_id, $time = REQUEST_TIME, $done = TRUE) {

  /**
   * Please read the comments in farm_log_query() to understand how this works,
   * and to be aware of the limitations and responsibilities we have in this
   * function with regard to sanitizing query inputs.
   */

  // Ensure $area_id is valid, because it will be used directly in the query
  // string. This is defensive code. See note about farm_log_query() above.
  if (!is_numeric($area_id) || $area_id < 0) {
    $area_id = db_escape_field($area_id);
  }

  // Use the farm_log_query() helper function to start a query object. Do not
  // limit the results to a single row because by the very nature of this we
  // want to find all assets in the area, which may come from multiple logs.
  $query = farm_log_query($time, $done, FALSE);

  // Join in the Movement field collection and filter to only include logs with
  // movements. Use an inner join to exclude logs that do not have a movement
  // field collection attached.
  $query->innerJoin('field_data_field_farm_movement', 'ss_fdffm', "ss_fdffm.entity_type = 'log' AND ss_fdffm.entity_id = ss_log.id AND ss_fdffm.deleted = 0");

  // Join in the movement's "move to" field, and filter to only include logs
  // that have a movement with a "move to" the specified area. Use an inner
  // join to exclude logs that do not have a "move to" area reference.
  $query->innerJoin('field_data_field_farm_move_to', 'ss_fdffmt', "ss_fdffmt.entity_type = 'field_collection_item' AND ss_fdffmt.bundle = 'field_farm_movement' AND ss_fdffmt.entity_id = ss_fdffm.field_farm_movement_value AND ss_fdffmt.deleted = 0");
  $query->where('ss_fdffmt.field_farm_move_to_tid = ' . $area_id);

  // Return the query object.
  return $query;
}

/**
 * Implements hook_action_info().
 */
function farm_movement_action_info() {
  return array(
    'farm_movement_asset_move_action' => array(
      'type' => 'farm_asset',
      'label' => t('Move'),
      'configurable' => TRUE,
      'triggers' => array('any'),
      'aggregate' => TRUE,
    ),
  );
}

/**
 * Configuration form for farm_movement_asset_move action.
 *
 * @param array $context
 *   The context passed into the action form function.
 * @param array $form_state
 *   The form state passed into the action form function.
 *
 * @return array
 *   Returns a form array.
 */
function farm_movement_asset_move_action_form(array $context, array $form_state) {

  // Date field.
  $form['date'] = array(
    '#type' => 'date_select',
    '#title' => t('Date'),
    '#date_format' => 'M j Y',
    '#date_type' => DATE_FORMAT_UNIX,
    '#date_year_range' => '-10:+3',
    '#default_value' => date('Y-m-d H:i', REQUEST_TIME),
    '#required' => TRUE,
  );

  // Area reference field.
  $form['areas'] = array(
    '#type' => 'textfield',
    '#title' => t('Location'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#required' => TRUE,
  );

  // Done field.
  $form['done'] = array(
    '#type' => 'checkbox',
    '#title' => t('This movement has taken place (mark the log as done)'),
    '#default_value' => TRUE,
  );

  // Return the form.
  return $form;
}

/**
 * Submit handler for farm_movement_asset_move action configuration form.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 *
 * @return array
 *   Returns an array that will end up in the action's context.
 */
function farm_movement_asset_move_action_submit(array $form, array $form_state) {

  // Start to build the context array.
  $context = array();

  // Load the areas.
  $context['areas'] = farm_movement_parse_areas($form_state['values']['areas']);

  // Convert the date to a timestamp.
  $timestamp = strtotime($form_state['values']['date']);

  // The action form only includes month, day, and year. If the movement is
  // today, then we assume that the current time should also be included.
  if (date('Ymd', $timestamp) == date('Ymd', REQUEST_TIME)) {
    $context['timestamp'] = REQUEST_TIME;
  }

  // Otherwise, the movement is in the past/future, so don't include a time.
  else {
    $context['timestamp'] = $timestamp;
  }

  // Copy the "done" value as a boolean.
  $context['done'] = !empty($form_state['values']['done']) ? TRUE : FALSE;

  // Return the context array.
  return $context;
}

/**
 * Action function for farm_movement_asset_move.
 *
 * Creates a new movement activity log for the specified assets.
 *
 * @param array $assets
 *   An array of asset entities to move.
 * @param array $context
 *   Array with parameters for this action.
 */
function farm_movement_asset_move_action(array $assets, $context = array()) {

  // If we're missing assets, areas, or a timestamp, bail.
  if (empty($assets) || empty($context['areas']) || empty($context['timestamp'])) {
    drupal_set_message('Could not perform movement because required information was missing.', 'error');
    return;
  }

  // Create a movement activity log.
  farm_movement_create($assets, $context['areas'], $context['timestamp'], 'farm_activity', $context['done']);
}

/**
 * Create a log for moving assets to areas.
 *
 * @param array|FarmAsset $assets
 *   Array of assets to include in the move.
 * @param array $areas
 *   An array of areas to move to.
 * @param int $timestamp
 *   The timestamp of the move. Defaults to the current time.
 * @param string $log_type
 *   The type of log to create. Defaults to "farm_observation".
 * @param bool $done
 *   Boolean indicating whether or not the log should be marked "done". Defaults
 *   to TRUE.
 *
 * @return \Log
 *   Returns the log that was created.
 */
function farm_movement_create($assets, $areas = array(), $timestamp = REQUEST_TIME, $log_type = 'farm_observation', $done = TRUE) {

  // If no areas are defined, bail.
  if (empty($areas)) {
    return;
  }

  // If $assets isn't an array, wrap it.
  if (!is_array($assets)) {
    $assets = array($assets);
  }

  // If the log is an observation, set the name to:
  // "Current location: [area(s)]".
  $log_name = '';
  if ($log_type == 'farm_observation') {
    $log_name = t('Current location') . ': ' . farm_log_entity_label_summary('taxonomy_term', $areas);
  }

  // Create a new farm log entity.
  $log = farm_log_create($log_type, $log_name, $timestamp, $done, $assets);

  // Create an entity wrapper for the log.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Create a new movement field_collection entity attached to the log.
  if (!empty($areas)) {
    $movement = entity_create('field_collection_item', array('field_name' => 'field_farm_movement'));
    $movement->setHostEntity('log', $log);
    foreach ($areas as $area) {
      $log_wrapper->field_farm_movement->field_farm_move_to[] = $area;
    }
  }

  // Create a new movement field_collection entity attached to the log.
  $movement = entity_create('field_collection_item', array('field_name' => 'field_farm_movement'));
  $movement->setHostEntity('log', $log);

  // Create an entity wrapper for the adjustment.
  $movement_wrapper = entity_metadata_wrapper('field_collection_item', $movement);

  // Iterate through the areas and add each to the "Move to" field.
  foreach ($areas as $area) {
    $movement_wrapper->field_farm_move_to[] = $area;
  }

  // Save the movement.
  $movement_wrapper->save();

  // Return the log.
  return $log;
}
