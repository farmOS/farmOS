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
    $pane = new stdClass();
    $pane->pid = 'new-9938add9-ddef-410b-8699-bcd71d668d7b';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_seeding-panel_pane_1';
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
    $pane->uuid = '9938add9-ddef-410b-8699-bcd71d668d7b';
    $display->content['new-9938add9-ddef-410b-8699-bcd71d668d7b'] = $pane;
    $display->panels['left'][1] = 'new-9938add9-ddef-410b-8699-bcd71d668d7b';
    $pane = new stdClass();
    $pane->pid = 'new-d496f492-d528-4869-8c2e-2157808042ee';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_transplanting-panel_pane_1';
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
    $pane->uuid = 'd496f492-d528-4869-8c2e-2157808042ee';
    $display->content['new-d496f492-d528-4869-8c2e-2157808042ee'] = $pane;
    $display->panels['left'][2] = 'new-d496f492-d528-4869-8c2e-2157808042ee';
    $pane = new stdClass();
    $pane->pid = 'new-5137f6c6-5556-4950-a570-7d80f655860e';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_input-panel_pane_1';
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
    $pane->position = 3;
    $pane->locks = array();
    $pane->uuid = '5137f6c6-5556-4950-a570-7d80f655860e';
    $display->content['new-5137f6c6-5556-4950-a570-7d80f655860e'] = $pane;
    $display->panels['left'][3] = 'new-5137f6c6-5556-4950-a570-7d80f655860e';
    $pane = new stdClass();
    $pane->pid = 'new-e79dd32b-0d6e-436a-abd9-b57be8da0d79';
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
    $pane->uuid = 'e79dd32b-0d6e-436a-abd9-b57be8da0d79';
    $display->content['new-e79dd32b-0d6e-436a-abd9-b57be8da0d79'] = $pane;
    $display->panels['right'][0] = 'new-e79dd32b-0d6e-436a-abd9-b57be8da0d79';
    $pane = new stdClass();
    $pane->pid = 'new-c960beef-70cf-49e7-a09a-d207bfb12082';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_log_harvest-panel_pane_1';
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
    $pane->uuid = 'c960beef-70cf-49e7-a09a-d207bfb12082';
    $display->content['new-c960beef-70cf-49e7-a09a-d207bfb12082'] = $pane;
    $display->panels['right'][1] = 'new-c960beef-70cf-49e7-a09a-d207bfb12082';
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
    $pane = new stdClass();
    $pane->pid = 'new-4adea4d4-c676-414a-bd80-3c0d50cd0b8c';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_plantings-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'context' => array(
        0 => 'argument_term_1',
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
    $pane->uuid = '4adea4d4-c676-414a-bd80-3c0d50cd0b8c';
    $display->content['new-4adea4d4-c676-414a-bd80-3c0d50cd0b8c'] = $pane;
    $display->panels['right'][0] = 'new-4adea4d4-c676-414a-bd80-3c0d50cd0b8c';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = 'new-58154fd8-d960-4d55-ab54-3fb0bc89d161';
  $handler->conf['display'] = $display;
  $export['term_view_panel_context_farm_crop'] = $handler;

  return $export;
}
