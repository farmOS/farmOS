<?php

/**
 * @file
 * Hooks provided by farm_ui_dashboard.
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
 * Defines farm dashboard panes.
 *
 * @return array
 *   Returns an array of farm dashboard pane configurations.
 */
function hook_farm_dashboard_panes() {
  return [

    // A pane is defined with an associative array, keyed with a unique pane
    // machine name.
    'my_pane' => [

      // Specify a 'view' and 'view_display ID' to automatically load a View.
      // This will take precedence over 'block' below.
      'view' => 'my_view',
      'view_display_id' => 'block_1',

      // Specify a block plugin ID to automatically load a Block.
      'block' => 'my_block',

      // Optional arguments to pass to the view or block.
      // This is useful if the view or block can be used in different contexts.
      'args' => [
        'arg1' => FALSE,
        'arg2' => 'logs',
      ],

      // Optionally specify a title. By default the view's title or the block's
      // label will be used.
      'title' => t('My pane title'),

      // Optionally specify a layout region. Defaults to 'first'.
      // Options are: top, first, second or bottom.
      'region' => 'top',

      // Optionally specify a group and weight for display sorting.
      'group' => 'custom_group',
      'weight' => 100,
    ],
  ];
}

/**
 * Defines farm dashboard groups.
 *
 * @return array
 *   Returns an array of farm dashboard groups keyed by layout region.
 */
function hook_farm_dashboard_groups() {
  return [

    // Returns an associate array keyed by the layout region.
    // Options are top, first, second, or bottom.
    'first' => [

      // Groups are defined as a render array keyed with a unique group
      // machine name.
      'my_group' => [

        // The type. Defaults to container.
        '#type' => 'details',

        // Optionally specify a title.
        '#title' => t('My group title'),

        // Optionally specify a weight for sorting.
        '#weight' => 100,
      ],

    ],
    'bottom' => [
      // Define groups for the bottom layout region.
    ],
  ];
}

/**
 * @} End of "addtogroup hooks".
 */
