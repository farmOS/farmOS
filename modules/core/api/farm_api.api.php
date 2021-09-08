<?php

/**
 * @file
 * Hooks provided by farm_api.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Alter the a "meta.farm" data in the root /api endpoint.
 *
 * @param array &$data
 *   The data to be altered.
 */
function hook_farm_api_meta_alter(array &$data) {

  // Add a custom key.
  $data['mykey'] = 'myvalue';
}

/**
 * @} End of "addtogroup hooks".
 */
