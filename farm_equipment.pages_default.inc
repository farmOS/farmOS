<?php
/**
 * @file
 * farm_equipment.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function farm_equipment_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'farm_asset_view_panel_context';
  $handler->task = 'farm_asset_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 2;
  $handler->conf = array(
    'title' => 'Equipment View',
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
              'equipment' => 'equipment',
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
    $pane->pid = 'new-60948175-91ca-413b-aeb0-dce040e7b166';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_maintenance-panel_pane_1';
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
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '60948175-91ca-413b-aeb0-dce040e7b166';
    $display->content['new-60948175-91ca-413b-aeb0-dce040e7b166'] = $pane;
    $display->panels['left'][1] = 'new-60948175-91ca-413b-aeb0-dce040e7b166';
    $pane = new stdClass();
    $pane->pid = 'new-08fcff53-cedf-42fe-a0e0-43cdc9f0e7ae';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_observation-panel_pane_1';
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
    $pane->position = 2;
    $pane->locks = array();
    $pane->uuid = '08fcff53-cedf-42fe-a0e0-43cdc9f0e7ae';
    $display->content['new-08fcff53-cedf-42fe-a0e0-43cdc9f0e7ae'] = $pane;
    $display->panels['left'][2] = 'new-08fcff53-cedf-42fe-a0e0-43cdc9f0e7ae';
    $pane = new stdClass();
    $pane->pid = 'new-7d838235-4b44-4466-a902-474308b0fd6b';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_movement-panel_pane_1';
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
    $pane->uuid = '7d838235-4b44-4466-a902-474308b0fd6b';
    $display->content['new-7d838235-4b44-4466-a902-474308b0fd6b'] = $pane;
    $display->panels['right'][0] = 'new-7d838235-4b44-4466-a902-474308b0fd6b';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $export['farm_asset_view_panel_context'] = $handler;

  return $export;
}
