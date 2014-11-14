<?php
/**
 * @file
 * farm_crop.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_handlers().
 */
function farm_crop_default_page_manager_handlers() {
  $export = array();

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'farm_asset_view_panel_context_planting';
  $handler->task = 'farm_asset_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 1;
  $handler->conf = array(
    'title' => 'Planting View',
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
              'planting' => 'planting',
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
  $display->uuid = '268775a7-7683-42ce-9968-82b0836368cf';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-b9cd042f-888f-4d29-9edb-3410303177c3';
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
    $pane->uuid = 'b9cd042f-888f-4d29-9edb-3410303177c3';
    $display->content['new-b9cd042f-888f-4d29-9edb-3410303177c3'] = $pane;
    $display->panels['left'][0] = 'new-b9cd042f-888f-4d29-9edb-3410303177c3';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-b9cd042f-888f-4d29-9edb-3410303177c3';
  $handler->conf['display'] = $display;
  $export['farm_asset_view_panel_context_planting'] = $handler;

  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'term_view_panel_context_farm_crop';
  $handler->task = 'term_view';
  $handler->subtask = '';
  $handler->handler = 'panel_context';
  $handler->weight = 2;
  $handler->conf = array(
    'title' => 'Farm Crop',
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
          'name' => 'term_vocabulary',
          'settings' => array(
            'machine_name' => array(
              'farm_crops' => 'farm_crops',
            ),
          ),
          'context' => 'argument_term_1',
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
  $display->title = '%term:name';
  $display->uuid = '1f4e4a4a-d41e-477d-9f9a-fec93a54c3fe';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-58154fd8-d960-4d55-ab54-3fb0bc89d161';
    $pane->panel = 'left';
    $pane->type = 'entity_view';
    $pane->subtype = 'taxonomy_term';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'view_mode' => 'full',
      'context' => 'argument_term_1',
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
    $pane->uuid = '58154fd8-d960-4d55-ab54-3fb0bc89d161';
    $display->content['new-58154fd8-d960-4d55-ab54-3fb0bc89d161'] = $pane;
    $display->panels['left'][0] = 'new-58154fd8-d960-4d55-ab54-3fb0bc89d161';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-58154fd8-d960-4d55-ab54-3fb0bc89d161';
  $handler->conf['display'] = $display;
  $export['term_view_panel_context_farm_crop'] = $handler;

  return $export;
}
