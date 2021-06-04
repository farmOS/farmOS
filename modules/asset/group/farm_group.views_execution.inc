<?php

/**
 * @file
 * Provides Views runtime hooks for farm_group.module.
 */

use Drupal\views\ViewExecutable;

/**
 * Implements hook_views_pre_view().
 */
function farm_group_views_pre_view(ViewExecutable $view, $display_id, array &$args) {

  // Alter the farm_asset View.
  if ($view->id() == 'farm_asset') {

    // Add the computed "Group" field before the "Location" field.
    $field_options = [
      'type' => 'entity_reference_label',
      'label' => t('Group'),
      'settings' => [
        'link' => TRUE,
      ],
    ];
    $field_id = $view->addHandler($display_id, 'field', 'asset', 'group', $field_options);
    farm_ui_views_sort_field($view, $display_id, $field_id, 'location', TRUE);
  }

  // Alter the farm_log View.
  if ($view->id() == 'farm_log') {

    // Do not alter the upcoming/late block displays.
    if (in_array($display_id, ['block_upcoming', 'block_late'])) {
      return;
    }

    // Make sure the is_group_assignment field exists.
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $definitions */
    $definitions = \Drupal::service('entity_field.manager')
      ->getBaseFieldDefinitions('log');
    if (isset($definitions['is_group_assignment'])) {

      // Get field name and table name.
      $definition = $definitions['is_group_assignment'];
      $field_name = $definition->getName();
      /** @var \Drupal\Core\Entity\Sql\DefaultTableMapping $table_mapping */
      $table_mapping = \Drupal::entityTypeManager()
        ->getStorage('log')
        ->getTableMapping();
      $table = $table_mapping->getFieldTableName($definition->getName());

      // Define filter options.
      $opts = [
        'operator' => '=',
        'value' => 'All',
        'exposed' => TRUE,
        'expose' => [
          'label' => t('Is group assignment'),
          'identifier' => 'is_group_assignment',
        ],
      ];
      // Add the filter.
      $filter_id = $view->addHandler($view->current_display, 'filter', $table, $field_name, $opts);

      // Move the is_group_assignment filter to render before the status filter.
      $filters = $view->getDisplay()->getOption('filters');
      if ($index = array_search('status', array_keys($filters))) {
        $filter = $filters[$filter_id];
        unset($filters[$filter_id]);
        // Use array_merge + array_splice instead of array_splice so we can
        // preserve the array keys.
        $filters = array_merge(array_slice($filters, 0, $index), [$filter_id => $filter], array_slice($filters, $index));
      }
      // Set the new sorted filters array.
      $view->getDisplay()->setOption('filters', $filters);
    }
  }
}
