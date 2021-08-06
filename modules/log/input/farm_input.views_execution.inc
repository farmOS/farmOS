<?php

/**
 * @file
 * Provides Views runtime hooks for farm_input module.
 */

use Drupal\views\ViewExecutable;

/**
 * Implements hook_views_pre_view().
 */
function farm_input_views_pre_view(ViewExecutable $view, $display_id, array &$args) {

  // Alter the farm_log View.
  if ($view->id() == 'farm_log') {

    // Only alter the page_type and page_asset displays.
    if (!in_array($display_id, ['page_type', 'page_asset'])) {
      return;
    }

    // Bail if not a view of input logs.
    if (!in_array('input', $args)) {
      return;
    }

    // Add a filter for the quantity material type.
    $table = 'log_field_data';
    $field = 'quantity_material_type';
    $filter_options = [
      'id' => 'material_type_target_id',
      'table' => $table,
      'field' => $field,
      'exposed' => TRUE,
      'expose' => [
        'label' => t('Material type'),
        'identifier' => $field,
        'multiple' => TRUE,
      ],
      'type' => 'select',
      'limit' => TRUE,
    ];
    $view->addHandler($display_id, 'filter', $table, $field, $filter_options);
  }
}
