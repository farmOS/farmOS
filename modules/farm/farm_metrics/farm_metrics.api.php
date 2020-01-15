<?php

/**
 * @file
 * Hooks provided by farm_metrics.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_metrics Farm metrics module integrations.
 *
 * Module integrations with the farm_metrics module.
 */

/**
 * @defgroup farm_metrics_hooks Farm metrics's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_metrics.
 */

/**
 * Defines farm metrics.
 *
 * @return array
 *   Returns an array of farm metrics. The key should be a unique metric
 *   name, and each should be an array of metric values including the
 *   following keys:
 *     - label: Translated metric label.
 *     - value: The metric's value.
 *     - link: A path to link the value to.
 *     - weight: Weight for ordering (optional - defaults to alphabetical).
 */
function hook_farm_metrics() {
  $metrics = array();

  $metrics['example'] = array(
    'label' => t('Example'),
    'value' => '100',
    'link' => 'farm/example',
    'weight' => -10,
  );

  return $metrics;
}

/**
 * @}
 */
