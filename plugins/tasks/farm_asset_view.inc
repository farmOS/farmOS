<?php

/**
 * @file
 * Handle the 'farm_asset view' override task.
 *
 * This plugin overrides farm/asset/%farm_asset and reroutes it to the page manager, where
 * a list of tasks can be used to service this request based upon criteria
 * supplied by access plugins.
 */

/**
 * Specialized implementation of hook_farm_asset_task_tasks(). See api-task.html for
 * more information.
 */
function farm_asset_farm_asset_view_page_manager_tasks() {
  return array(
    // This is a 'page' task and will fall under the page admin UI
    'task type' => 'page',

    'title' => t('farm_asset template'),

    'admin title' => t('farm_asset template'),
    'admin description' => t('When enabled, this overrides the default Drupal behavior for displaying farm assets at <em>farm/asset/%farm_asset</em>. If you add variants, you may use selection criteria such as farm asset type or language or user access to provide different views of farm assets. If no variant is selected, the default Drupal farm asset view will be used. This page only affects farm assets viewed as pages, it will not affect farm assets viewed in lists or at other locations. Also please note that if you are using pathauto, aliases may make a farm asset to be somewhere else, but as far as Drupal is concerned, they are still at farm/asset/%farm_asset.'),
    'admin path' => 'farm/asset/%farm_asset',

    // Menu hooks so that we can alter the farm/asset/%farm_asset menu entry to point to us.
    'hook menu' => 'farm_asset_farm_asset_view_menu',
    'hook menu alter' => 'farm_asset_farm_asset_view_menu_alter',

    // This is task uses 'context' handlers and must implement these to give the
    // handler data it needs.
    'handler type' => 'context',
    'get arguments' => 'farm_asset_farm_asset_view_get_arguments',
    'get context placeholders' => 'farm_asset_farm_asset_view_get_contexts',

    // Allow this to be enabled or disabled:
    'disabled' => variable_get('farm_asset_farm_asset_view_disabled', FALSE),
    'enable callback' => 'farm_asset_farm_asset_view_enable',
    'access callback' => 'farm_asset_farm_asset_view_access_check',
  );
}

/**
 * Callback defined by farm_asset_farm_asset_view_farm_asset_tasks().
 *
 * Alter the farm_asset view input so that farm_asset view comes to us rather than the
 * normal farm_asset view process.
 */
function farm_asset_farm_asset_view_menu_alter(&$items, $task) {
  if (variable_get('farm_asset_farm_asset_view_disabled', TRUE)) {
    return;
  }

  // Override the farm_asset view handler for our purpose.
  $callback = $items['farm/asset/%farm_asset']['page callback'];
  if ($callback == 'farm_asset_view' || variable_get('farm_asset_override_anyway', FALSE)) {
    $items['farm/asset/%farm_asset']['page callback'] = 'farm_asset_farm_asset_view_page';
    $items['farm/asset/%farm_asset']['file path'] = $task['path'];
    $items['farm/asset/%farm_asset']['file'] = $task['file'];
  }
  else {
    // automatically disable this task if it cannot be enabled.
    variable_set('farm_asset_farm_asset_view_disabled', TRUE);
    if (!empty($GLOBALS['farm_asset_enabling_farm_asset_view'])) {
      drupal_set_message(t('Page manager module is unable to enable farm/asset/%farm_asset because some other module already has overridden with %callback.', array('%callback' => $callback)), 'error');
    }
  }

  // @todo override farm_asset revision handler as well?
}

/**
 * Entry point for our overridden farm_asset view.
 *
 * This function asks its assigned handlers who, if anyone, would like
 * to run with it. If no one does, it passes through to Drupal core's
 * farm_asset view, which is farm_asset_view().
 */
function farm_asset_farm_asset_view_page($farm_asset) {
  // Load farm_asset task plugin
  $task = page_manager_get_task('farm_asset_view');

  // Load the farm_asset into a context.
  ctools_include('context');
  ctools_include('context-task-handler');

  // We need to mimic Drupal's behavior of setting the farm_asset title here.
  drupal_set_title($farm_asset->name);
  $uri = entity_uri('farm_asset', $farm_asset);
  // Set the farm_asset path as the canonical URL to prevent duplicate content.
  drupal_add_html_head_link(array('rel' => 'canonical', 'href' => url($uri['path'], $uri['options'])), TRUE);
  // Set the non-aliased path as a default shortlink.
  drupal_add_html_head_link(array('rel' => 'shortlink', 'href' => url($uri['path'], array_merge($uri['options'], array('alias' => TRUE)))), TRUE);
  $contexts = ctools_context_handler_get_task_contexts($task, '', array($farm_asset));

  $output = ctools_context_handler_render($task, '', $contexts, array($farm_asset->id));
  if ($output != FALSE) {
    //farm_asset_tag_new($farm_asset);
    return $output;
  }

  module_load_include('inc', 'farm_asset', 'farm_asset.pages');
  $function = 'farm_asset_view';
  foreach (module_implements('farm_asset_override') as $module) {
    $call = $module . '_farm_asset_override';
    if (($rc = $call('farm_asset_view')) && function_exists($rc)) {
      $function = $rc;
      break;
    }
  }

  // Otherwise, fall back.
  return $function($farm_asset);
}

/**
 * Callback to get arguments provided by this task handler.
 *
 * Since this is the farm_asset view and there is no UI on the arguments, we
 * create dummy arguments that contain the needed data.
 */
function farm_asset_farm_asset_view_get_arguments($task, $subtask_id) {
  return array(
    array(
      'keyword' => 'farm_asset',
      'identifier' => t('farm_asset being viewed'),
      'id' => 1,
      'name' => 'entity_id:farm_asset',
      'settings' => array(),
    ),
  );
}

/**
 * Callback to get context placeholders provided by this handler.
 */
function farm_asset_farm_asset_view_get_contexts($task, $subtask_id) {
  return ctools_context_get_placeholders_from_argument(farm_asset_farm_asset_view_get_arguments($task, $subtask_id));
}

/**
 * Callback to enable/disable the page from the UI.
 */
function farm_asset_farm_asset_view_enable($cache, $status) {
  variable_set('farm_asset_farm_asset_view_disabled', $status);

  // Set a global flag so that the menu routine knows it needs
  // to set a message if enabling cannot be done.
  if (!$status) {
    $GLOBALS['farm_asset_enabling_farm_asset_view'] = TRUE;
  }
}

/**
 * Callback to determine if a page is accessible.
 *
 * @param $task
 *   The task plugin.
 * @param $subtask_id
 *   The subtask id
 * @param $contexts
 *   The contexts loaded for the task.
 * @return
 *   TRUE if the current user can access the page.
 */
function farm_asset_farm_asset_view_access_check($task, $subtask_id, $contexts) {
  $context = reset($contexts);
  return farm_asset_access('view', $context->data);
}