<?php
/**
 * @file
 * farm_livestock.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function farm_livestock_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'farm_asset_view_panel_context';
  $handler->task = 'farm_asset_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Animal View',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
    'access' => array(
      'plugins' => array(
        0 => array(
          'name' => 'entity_bundle:farm_asset',
          'settings' => array(
            'type' => array(
              'animal' => 'animal',
            ),
          ),
          'context' => 'argument_entity_id:farm_asset_1',
          'not' => FALSE,
        ),
      ),
      'logic' => 'and',
    ),
  );
  $display = new panels_display();
  $display->layout = 'twocol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = '%farm_asset:name';
  $display->uuid = '83d057c3-0fe2-4a8f-951b-a86276093113';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-14a5b1a8-a389-4c09-badf-1d90f1a93d3e';
    $pane->panel = 'left';
    $pane->type = 'entity_view';
    $pane->subtype = 'farm_asset';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'argument_entity_id:farm_asset_1',
      'override_title' => 0,
      'override_title_text' => '',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '14a5b1a8-a389-4c09-badf-1d90f1a93d3e';
    $display->content['new-14a5b1a8-a389-4c09-badf-1d90f1a93d3e'] = $pane;
    $display->panels['left'][0] = 'new-14a5b1a8-a389-4c09-badf-1d90f1a93d3e';
    $pane = new stdClass();
    $pane->pid = 'new-4d11e8fc-c85d-4796-9caa-4483bdeb27a7';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_asset_movements-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => array(
        0 => 'argument_entity_id:farm_asset_1',
      ),
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '4d11e8fc-c85d-4796-9caa-4483bdeb27a7';
    $display->content['new-4d11e8fc-c85d-4796-9caa-4483bdeb27a7'] = $pane;
    $display->panels['right'][0] = 'new-4d11e8fc-c85d-4796-9caa-4483bdeb27a7';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['farm_asset_view_panel_context'] = $handler;

  return $export;
}
