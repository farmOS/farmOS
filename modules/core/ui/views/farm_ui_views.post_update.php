<?php

/**
 * @file
 * Post update functions for farm_ui_views module.
 */

/**
 * Enable collapsible_filter views display extender.
 */
function farm_ui_views_post_update_enable_collapsible_filter(&$sandbox = NULL) {

  // Enable the collapsible_filter views display extender.
  $views_settings = \Drupal::configFactory()->getEditable('views.settings');
  $display_extenders = $views_settings->get('display_extenders');

  // Only enable if not already configured.
  if (!isset($display_extenders['collapsible_filter'])) {
    $display_extenders['collapsible_filter'] = 'collapsible_filter';
  }

  $views_settings->set('display_extenders', $display_extenders)->save();
}
