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
    $options = array(
      'query' => array(
        'destination' => 'farm/asset/' . $asset->id,
        'farm_asset' => $asset->id,
      ),
    );
    $link = l(t('add a movement'), 'log/add/farm_movement', $options);
    $output .= 'N/A (' . $link . ')';
  }

  // Get the asset's most recent movement.
  $movement = farm_log_movement_asset_latest_movement($asset);

  // If a geofield exists on the movement, display it.
  if (!empty($movement->field_farm_geofield[LANGUAGE_NONE][0]['geom'])) {

    // Build the geofield map and add it to the page content.
    $field_instance = field_info_instance('log', 'field_farm_geofield', 'farm_movement');
    $geofield = field_view_field('log', $movement, 'field_farm_geofield', $field_instance['display']['default']);
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
    '#description' => t('Set the current areas(s) that this asset is in. Separate multiple areas with commas. Asset location is determined by movement logs, so a movement log will automatically be generated if you change this field.'),
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

  // Create a movement log.
  farm_log_movement_move_assets($asset, $areas, REQUEST_TIME, TRUE);
}

/**
 * Find the location of an asset.
 *
 * @param FarmAsset $asset
 *   The farm_asset object to look for.
 * @param int $time
 *   Unix timestamp limiter. Only movement logs before this time will be
 *   included. Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show movement logs that are marked as "done".
 *   Defaults to TRUE.
 *
 * @return array
 *   Returns an array of areas that the asset is in.
 */
function farm_log_movement_asset_location(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {
  $areas = array();
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'log')
    ->entityCondition('bundle', 'farm_movement')
    ->fieldCondition('field_farm_asset', 'target_id', $asset->id)
    ->propertyOrderBy('timestamp', 'DESC')
    ->propertyOrderBy('id', 'DESC')
    ->range(0, 1);
  if (!empty($time)) {
    $query->propertyCondition('timestamp', $time, '<=');
  }
  if (!empty($done)) {
    $query->propertyCondition('done', TRUE);
  }
  $result = $query->execute();
  if (!empty($result['log'])) {
    foreach ($result['log'] as $id => $entity) {
      $log = log_load($id);
      foreach ($log->field_farm_move_to[LANGUAGE_NONE] as $area_reference) {
        if (!empty($area_reference['tid'])) {
          $term = taxonomy_term_load($area_reference['tid']);
          if (!empty($term)) {
            $areas[] = $term;
          }
        }
      }
    }
  }
  return $areas;
}

/**
 * Find the latest movement of an asset.
 *
 * @param FarmAsset $asset
 *   The farm_asset object to look for.
 * @param int $time
 *   Unix timestamp limiter. Only movement logs before this time will be
 *   included. Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show movement logs that are marked as "done".
 *   Defaults to TRUE.
 *
 * @return Log|bool
 *   Returns a movement log entity. FALSE if something goes wrong.
 */
function farm_log_movement_asset_latest_movement(FarmAsset $asset, $time = REQUEST_TIME, $done = TRUE) {

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
 * Build a query to find the latest movement log of an asset.
 *
 * @param int|string $asset_id
 *   The asset id to search for. This can either be a specific id, or a field
 *   alias string from another query (ie: 'mytable.assetid'). For an example
 *   of field alias string usage, see the Views field handler code in
 *   farm_log_handler_relationship_location::query().
 * @param int $time
 *   Unix timestamp limiter. Only movement logs before this time will be
 *   included. Defaults to the current time. Set to 0 to load the absolute last.
 * @param bool $done
 *   Whether or not to only show movement logs that are marked as "done".
 *   Defaults to TRUE.
 *
 * @return \SelectQuery
 *   Returns a SelectQuery for finding the latest log.
 */
function farm_log_movement_asset_movement_query($asset_id, $time = REQUEST_TIME, $done = TRUE) {

  // Build a sub-query that will be used in the join to load the latest
  // movement log of a given asset.
  $query = db_select('log', 'ss_log');
  $query->join('field_data_field_farm_asset', 'ss_fdffa', 'ss_log.id = ss_fdffa.entity_id');
  $query->where("ss_log.type = 'farm_movement'");
  $query->where('ss_fdffa.field_farm_asset_target_id = ' . $asset_id);
  $query->where('ss_fdffa.deleted = 0');
  $query->orderBy('ss_log.timestamp', 'DESC');
  $query->orderBy('ss_log.id', 'DESC');
  $query->range(0, 1);
  $query->addField('ss_log', 'id');

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
 * Creates a new movement record for the specified assets.
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
 * Move farm asset(s) to an area. Creates a movement log.
 *
 * @param array|FarmAsset $assets
 *   Array of assets to include in the move.
 * @param array $areas
 *   An array of areas to move to.
 * @param int $timestamp
 *   The timestamp of the move. Defaults to the current time.
 * @param bool $done
 *   Whether or not to mark the movement done. Default to FALSE.
 */
function farm_log_movement_move_assets($assets, $areas, $timestamp = REQUEST_TIME, $done = FALSE) {

  // If $assets isn't an array, wrap it.
  if (!is_array($assets)) {
    $assets = array($assets);
  }

  // Create a new movement log entity.
  $log = entity_create('log', array('type' => 'farm_movement'));

  // Iterate through the assets.
  foreach ($assets as $asset) {

    // Add the asset to the asset reference field.
    $log->field_farm_asset[LANGUAGE_NONE][] = array(
      'target_id' => $asset->id,
    );
  }

  // Populate the movement's "From" field based on the current asset location(s).
  farm_log_movement_prepopulate_movement_from($log->field_farm_move_from, $assets);

  // Set the date.
  $log->timestamp = $timestamp;

  // Set the "to" area(s).
  foreach ($areas as $area) {
    if (!empty($area->tid)) {
      $log->field_farm_move_to[LANGUAGE_NONE][] = array(
        'tid' => $area->tid,
      );
    }
  }

  // Set the log's done status.
  $log->done = $done;

  // Save the movement.
  log_save($log);
}

/**
 * Populate a movement's "from" field based on current asset location(s).
 *
 * @param array $from_field
 *   A reference to the field on the movement log. The values in this array
 *   will be populated.
 * @param array $assets
 *   An array of FarmAsset entities.
 * @param bool $log_form
 *   By default, this function assumes that it is working with a $log entity.
 *   By setting $log_form to TRUE, it can also be used to alter the default
 *   value of the "from" field in a log edit form.
 */
function farm_log_movement_prepopulate_movement_from(&$from_field, $assets = array(), $log_form = FALSE) {

  // If assets are empty, bail.
  if (empty($assets)) {
    return;
  }

  // Keep track of what areas these assets are coming from.
  $from_areas = array();

  // If this is a log form, start with an empty array for default values.
  if ($log_form) {
    $form_default_values = array();
  }

  // Iterate through the assets.
  foreach ($assets as $asset) {

    // Load the asset's current location.
    $areas = farm_log_movement_asset_location($asset);

    // If the asset has a current location, add the location area names to the
    // "from" field.
    if (!empty($areas)) {
      foreach ($areas as $area) {

        // Avoid adding the same area more than once.
        if (in_array($area->tid, $from_areas)) {
          continue;
        }

        // Assuming that this is an entity object, and not a log form, add area
        // term ids to the array in the expected format.
        if (!$log_form) {
          $from_field[LANGUAGE_NONE][] = array(
            'tid' => $area->tid,
          );
        }

        // If this is a log form, assemble the list of areas as a comma-separated
        // string (to be added to the #default_value below).
        else {
          $form_default_values[] = $area->name;
        }

        $from_areas[] = $area->tid;
      }
    }
  }

  // Finally, if this is a log form, and there are default values...
  if ($log_form && !empty($form_default_values)) {

    // If any of the areas have commas in their name, wrap them in quotes.
    foreach ($form_default_values as &$value) {
      if (strpos($value, ',') !== FALSE) {
        $value = '"' . $value . '"';
      }
    }

    // Implode the values into a comma-separated string and set the form field's
    // default value.
    $from_field[LANGUAGE_NONE]['#default_value'] = implode(', ', $form_default_values);
  }
}
