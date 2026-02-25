<?php

/**
 * @file
 * Hooks provided by farm_ui_action.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

declare(strict_types=1);

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Declares entity actions to expose as action links.
 *
 * @return array
 *   Returns an array of entity action IDs.
 */
function hook_farm_exposed_entity_actions() {

  // Expose the "Clone asset" and "Archive asset" actions.
  return [
    'asset_clone_action',
    'asset_archive_action',
  ];
}

/**
 * @} End of "addtogroup hooks".
 */
