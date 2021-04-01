<?php

/**
 * @file
 * Provide Views data for the basic data stream.
 */

/**
 * Implements hook_views_data().
 *
 * Views integration for basic data stream data.
 */
function data_stream_views_data() {
  $data = [];

  /** @var \Drupal\data_stream\DataStreamTypeManager $manager */
  $manager = \Drupal::service('plugin.manager.data_stream_type');

  // Collect views data from all data stream type plugins.
  $data_stream_types = $manager->getDefinitions();
  foreach (array_keys($data_stream_types) as $plugin_id) {
    /** @var \Drupal\data_stream\Plugin\DataStream\DataStreamType\DataStreamTypeInterface $plugin */
    $plugin = $manager->createInstance($plugin_id);
    $data = array_replace_recursive($data, $plugin->getViewsData());
  }

  return $data;
}
