<?php
/**
 * @file
 * farm_equipment_field.field_group.inc
 */

/**
 * Implements hook_field_group_info_alter().
 */
function farm_equipment_field_field_group_info_alter(&$groups) {

  // Iterate through the groups and make a list of asset and general groups.
  $asset_groups = array();
  $general_groups = array();
  foreach ($groups as $key => $group) {

    // If we don't know the entity type, bail.
    if (empty($group->entity_type)) {
      continue;
    }

    // Only alter logs.
    if ($group->entity_type != 'log') {
      continue;
    }

    // Only alter the form.
    if ($group->mode != 'form') {
      continue;
    }

    // Add it to one of the listed based on its name.
    switch ($group->group_name) {
      case 'group_farm_assets':
        $asset_groups[] = $key;
        break;
      case 'group_farm_general':
        $general_groups[] = $key;
        break;
    }
  }

  // Remove general groups on log types that have an assets group.
  foreach ($asset_groups as $key) {

    // Get the asset group from the original groups array.
    $asset_group = $groups[$key];

    // Assemble the identifier for this bundle's general group.
    $parts = array(
      'group_farm_general',
      $asset_group->entity_type,
      $asset_group->bundle,
      $asset_group->mode,
    );
    $general_group_key = implode('|', $parts);

    // Remove the general group.
    $key = array_search($general_group_key, $general_groups);
    if ($key !== FALSE) {
      unset($general_groups[$key]);
    }
  }

  // Merge the asset and general groups into a final list of groups we want to
  // add the equipment field to.
  $alter_groups = array_merge($asset_groups, $general_groups);

  // Iterate through the groups we want to alter.
  foreach ($alter_groups as $key) {

    // Add the "Equipment used" field.
    $groups[$key]->data['children'][] = "field_farm_equipment";
  }
}
