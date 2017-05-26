<?php
/**
 * @file
 * farm_map.default_openlayers_interactions.inc
 */

/**
 * Implements hook_default_openlayers_interactions_alter().
 */
function farm_map_default_openlayers_interactions_alter(&$interactions) {

  // If the default dragpan interaction exists alter it.
  if (!empty($interactions['openlayers_interaction_dragpan'])) {

    // Set the delay to zero.
    $interactions['openlayers_interaction_dragpan']->options['delay'] = 0;
  }
}
