<?php

/**
 * @file
 * Provides Views runtime hooks for farm_ui_views.module.
 */

use Drupal\Core\Entity\Sql\SqlContentEntityStorageException;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Implements hook_views_pre_view().
 */
function farm_ui_views_views_pre_view(ViewExecutable $view, $display_id, array &$args) {

  // We only want to alter the Views we provide.
  if (!in_array($view->id(), ['farm_asset', 'farm_log', 'farm_plan', 'farm_quantity'])) {
    return;
  }

  // If this is a "By type" display, alter the fields and filters.
  $bundle = farm_ui_views_get_bundle_argument($view, $display_id, $args);
  if (!empty($bundle)) {

    // Remove the type field and filter handlers.
    $view->removeHandler($display_id, 'field', 'type');
    $view->removeHandler($display_id, 'filter', 'type');

    // Add the type to exposed filters.
    // This ensures the "Export CSV" link includes a type filter.
    $exposed_input = $view->getExposedInput();
    $exposed_input['type'] = [$bundle];
    $view->setExposedInput($exposed_input);

    // If the entity type has a bundle_plugin manager, add all of its
    // bundle fields and filters to the page_type view.
    if (\Drupal::entityTypeManager()->hasHandler($view->getBaseEntityType()->id(), 'bundle_plugin')) {
      farm_ui_views_add_bundle_handlers($view, $display_id, $bundle, 'field');
      farm_ui_views_add_bundle_handlers($view, $display_id, $bundle, 'filter');
    }
  }

  // If this is an asset/log/quantity "CSV export" display with a single type
  // filter applied, alter fields and filters to match the "By type" display.
  if (in_array($view->id(), ['farm_asset', 'farm_log', 'farm_quantity']) && $display_id == 'csv') {

    // Determine the bundle from the "type" exposed input filter.
    // The input may be a string or an array depending on if the exposed filter
    // allows multiple selections.
    $bundle = NULL;
    $exposed_input = $view->getExposedInput();
    if (isset($exposed_input['type'])) {
      if (is_string($exposed_input['type']) && $exposed_input['type'] != 'All') {
        $bundle = $exposed_input['type'];
      }
      elseif (is_array($exposed_input['type']) && count($exposed_input['type']) == 1) {
        $bundle = $exposed_input['type'][0];
      }
    }

    // If the entity type has a bundle_plugin manager, add all of its
    // bundle fields and filters to the page_type view.
    if ($bundle && \Drupal::entityTypeManager()->hasHandler($view->getBaseEntityType()->id(), 'bundle_plugin')) {
      farm_ui_views_add_bundle_handlers($view, $display_id, $bundle, 'field');
      farm_ui_views_add_bundle_handlers($view, $display_id, $bundle, 'filter');
    }
  }

  // If this is a log "CSV export" display, set the Quantity view mode to
  // "Plain text" to strip out tags and whitespace.
  if ($view->id() == 'farm_log' && $display_id == 'csv') {
    $view->setHandlerOption('csv', 'field', 'quantity_target_id', 'settings', ['view_mode' => 'plain_text']);
  }

  // Remove the asset and location filters from the log page_asset display.
  // @todo Make the AssetOrLocationArgument compatible with these filters.
  if ($view->id() == 'farm_log' && $display_id == 'page_asset') {
    $view->removeHandler($display_id, 'filter', 'asset_target_id');
    $view->removeHandler($display_id, 'filter', 'location_target_id');
  }

  // If this is the "Upcoming" or "Late" Logs block display, add a "more" link
  // that points to the default page display with appropriate filters.
  if ($view->id() == 'farm_log' && in_array($display_id, ['block_upcoming', 'block_late'])) {
    $view->display_handler->setOption('use_more', TRUE);
    $view->display_handler->setOption('use_more_always', TRUE);
    $view->display_handler->setOption('link_display', 'custom_url');
    $today = date('Y-m-d', \Drupal::time()->getRequestTime());
    if ($display_id == 'block_upcoming') {
      $view->display_handler->setOption('use_more_text', t('View all upcoming logs'));
      $view->display_handler->setOption('link_url', 'logs?status[]=pending&start=' . $today);
    }
    elseif ($display_id == 'block_late') {
      $view->display_handler->setOption('use_more_text', t('View all late logs'));
      $view->display_handler->setOption('link_url', 'logs?status[]=pending&end=' . $today);
    }
  }
}

/**
 * Implements hook_views_pre_render().
 */
function farm_ui_views_views_pre_render(ViewExecutable $view) {

  // We only want to alter the Views we provide.
  if (!in_array($view->id(), ['farm_asset', 'farm_log', 'farm_plan', 'farm_quantity'])) {
    return;
  }

  // We may set the View page title, but assume not.
  $title = '';

  // If this is the farm_asset View and page_children display, include the
  // asset's name.
  if ($view->id() == 'farm_asset' && $view->current_display == 'page_children') {
    $asset_id = $view->args[0];
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset_id);
    if (!empty($asset)) {
      $title = t('Children of %asset', ['%asset' => $asset->label()]);
    }
  }

  // If this is the farm_asset View and page_location display, include the
  // asset's name.
  if ($view->id() == 'farm_asset' && $view->current_display == 'page_location') {
    $asset_id = $view->args[0];
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset_id);
    if (!empty($asset)) {
      $title = t('Assets in %location', ['%location' => $asset->label()]);
    }
  }

  // If this is the farm_log View and page_asset display, include the asset's
  // name.
  if ($view->id() == 'farm_log' && $view->current_display == 'page_asset') {
    $asset_id = $view->args[0];
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset_id);
    if (!empty($asset)) {
      $title = $asset->label() . ' ' . $view->getBaseEntityType()->getPluralLabel();
    }
  }

  // If this is a "By type" display and a bundle argument is specified, load
  // the bundle label and set the title.
  $bundle = farm_ui_views_get_bundle_argument($view, $view->current_display, $view->args);
  if (!empty($bundle)) {
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($view->getBaseEntityType()->id());
    if (!empty($bundles[$bundle])) {
      $title = $bundles[$bundle]['label'] . ' ' . $view->getBaseEntityType()->getPluralLabel();
    }
  }

  // If this is the farm_asset/farm_log View and page_term display, include
  // the term's name.
  if (in_array($view->id(), ['farm_asset', 'farm_log']) && $view->current_display == 'page_term') {
    $term_id = $view->args[0];
    $entity_bundle = $view->args[1];
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term_id);

    if (!empty($term)) {
      $vocabulary = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->load($term->bundle());

      $entity_bundle_label = '';
      if ($entity_bundle != 'all') {
        $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($view->getBaseEntityType()->id());
        if (!empty($bundles[$entity_bundle])) {
          $entity_bundle_label = $bundles[$entity_bundle]['label'] . ' ' . $view->getBaseEntityType()->getPluralLabel();
        }
      }

      if (!empty($entity_bundle_label)) {
        $title = t('%bundle with %vocab term %term', [
          '%bundle' => $entity_bundle_label,
          '%vocab' => $vocabulary->label(),
          '%term' => $term->label(),
        ]);
      }
      else {
        $title = t('%base_type with %vocab term %term', [
          '%base_type' => $view->getBaseEntityType()->getCollectionLabel(),
          '%vocab' => $vocabulary->label(),
          '%term' => $term->label(),
        ]);
      }
    }
  }

  // Set the title, if so desired.
  if (!empty($title)) {
    $view->setTitle($title);
  }
}

/**
 * Helper function for adding bundle-specific field and filter handlers.
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The View to add handlers to.
 * @param string $display_id
 *   The ID of the View display to add handlers to.
 * @param string $bundle
 *   The bundle name.
 * @param string $type
 *   The handler type ('field' or 'filter').
 */
function farm_ui_views_add_bundle_handlers(ViewExecutable $view, string $display_id, string $bundle, string $type) {

  // Get the entity and bundle.
  $base_entity = $view->getBaseEntityType();

  // Get the entity storage and table mapping.
  /** @var \Drupal\Core\Entity\Sql\SqlContentEntityStorage $entity_storage */
  $entity_storage = \Drupal::entityTypeManager()->getStorage($base_entity->id());
  $table_mapping = $entity_storage->getTableMapping();

  // Load bundle fields.
  /** @var \Drupal\entity\BundleFieldDefinition[] $bundle_fields */
  $bundle_fields = \Drupal::entityTypeManager()->getHandler($base_entity->id(), 'bundle_plugin')->getFieldDefinitions($bundle);
  foreach (array_reverse($bundle_fields) as $field_name => $field_definition) {

    // Skip the bundle field if the view display was set as "hidden".
    $view_options = $field_definition->getDisplayOptions('view');
    if (empty($view_options) || (!empty($view_options['region']) && $view_options['region'] == 'hidden')) {
      continue;
    }

    // Save the field type.
    $field_type = $field_definition->getType();

    // Build a views option name so we can load its views data definition.
    // First try to get the table from the field's table mapping.
    try {
      $table = $table_mapping->getFieldTableName($field_name);
    }
    // Else default to the entity types's base table.
    // This is the convention that computed fields should follow when defining
    // views data since they do not have a table created in the database.
    catch (SqlContentEntityStorageException $e) {
      $table = $entity_storage->getBaseTable();
    }

    // Build the column name from the field name + main property.
    $property_name = $field_definition->getFieldStorageDefinition()->getMainPropertyName();
    $column_name = $field_name . '_' . $property_name;

    // Fraction fields do not have a main property name, so build it manually.
    if ($field_type == 'fraction') {
      $column_name = $field_name . '_value';
    }

    // Combine the table and column names.
    $views_option_name = $table . '.' . $column_name;

    // Add a field handler if a views data field definition exists.
    if ($type == 'field') {
      $field_options = Views::viewsDataHelper()->fetchFields($table, 'field');
      if (isset($field_options[$views_option_name])) {

        // Build field options for the field type.
        $field_options = [];
        $sort_order = 'asc';

        // Get the field label.
        $field_options['label'] = $field_definition->getLabel();

        // Add settings that are specific to field types.
        switch ($field_type) {

          case 'timestamp':
            // Render timestamp fields in the html_date format.
            $field_options['type'] = 'timestamp';
            $field_options['settings']['date_format'] = 'html_date';

            // Sort timestamps descending so new dates are on top by default.
            $sort_order = 'desc';
            break;

          case 'boolean':
          case 'entity_reference':
          case 'fraction':
          case 'list_string':
          case 'string':
            // Field types that do not need any modifications.
            break;

          default:
            // Do not add field handlers for unsupported field types.
            continue 2;
        }

        // Add the field handler.
        $new_field_id = $view->addHandler($display_id, 'field', $table, $column_name, $field_options);

        // Determine what position to insert the field handler.
        switch ($base_entity->id()) {
          case 'asset':
          case 'plan':
            $sort_field = 'name';
            break;

          case 'log':
            $sort_field = 'quantity_target_id';
            break;

          case 'quantity':
          default:
            $sort_field = FALSE;
            break;
        }

        // Sort the field handlers if necessary.
        if (!empty($sort_field)) {
          farm_ui_views_sort_field($view, $display_id, $new_field_id, $sort_field);
        }

        // Add the field to the table style options.
        $view->getStyle()->options['columns'][$new_field_id] = $new_field_id;
        $view->getStyle()->options['info'][$new_field_id] = [
          'sortable' => TRUE,
          'default_sort_order' => $sort_order,
          'align' => '',
          'separator' => '',
          'empty_column' => TRUE,
          'responsive' => '',
        ];
      }
    }

    // Add a filter handler if a views data filter definition exists.
    elseif ($type == 'filter') {
      $filter_options = Views::viewsDataHelper()->fetchFields($table, 'filter');
      if (isset($filter_options[$views_option_name])) {
        $filter_options = [
          'id' => $field_name,
          'table' => $table,
          'field' => $column_name,
          'exposed' => TRUE,
          'expose' => [
            'operator_id' => $column_name . '_op',
            'label' => $filter_options[$views_option_name]['title'],
            'identifier' => $column_name,
            'multiple' => TRUE,
          ],
          'entity_type' => $base_entity->id(),
          'entity_field' => $field_name,
        ];

        // Build filter options for the field type.
        switch ($field_type) {

          case 'boolean':
            $filter_options['value'] = 'All';
            break;

          case 'entity_reference':
            $target_type = $field_definition->getSetting('target_type');

            // Use a select widget for taxonomy term references.
            if ($target_type === 'taxonomy_term') {
              $filter_options['type'] = 'select';

              // Limit to specific vocabularies if configured.
              $handler_settings = $field_definition->getSetting('handler_settings');
              $filter_options['limit'] = FALSE;
              if (!empty($handler_settings['target_bundles'])) {
                $filter_options['limit'] = TRUE;
                $filter_options['vid'] = reset($handler_settings['target_bundles']);
              }
            }

            // We use autocomplete for other entity types.
            else {
              $filter_options['type'] = 'autocomplete';

              // If Views is used for the handler, or if target bundles are
              // specified, then pass the handler and handler settings through
              // to the filter so they can be used to limit options.
              $handler = $field_definition->getSetting('handler');
              $handler_settings = $field_definition->getSetting('handler_settings');
              if ($handler === 'views' || !empty($handler_settings['target_bundles'])) {
                $filter_options['handler'] = $handler;
                $filter_options['handler_settings'] = $handler_settings;
              }
            }

            break;

          case 'string':
            // String fields use the contains operator.
            $filter_options['operator'] = 'contains';
            break;

          case 'list_string':
          case 'timestamp':
            // Field types that do not need any modifications.
            break;

          default:
            // Do not add filter handlers for unsupported field types.
            continue 2;
        }

        // Add the filter handler.
        $view->addHandler($display_id, 'filter', $table, $column_name, $filter_options);
      }
    }
  }
}
