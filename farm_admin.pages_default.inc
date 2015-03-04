<?php
/**
 * @file
 * farm_admin.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_pages().
 */
function farm_admin_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'farm_admin';
  $page->task = 'page';
  $page->admin_title = 'Farm Plan';
  $page->admin_description = 'An administrative dashboard for farm managers.';
  $page->path = 'farm/plan';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'perm',
        'settings' => array(
          'perm' => 'access farm dashboard',
        ),
        'context' => 'logged-in-user',
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
  );
  $page->menu = array(
    'type' => 'default tab',
    'title' => 'Plan',
    'name' => 'management',
    'weight' => '-10',
    'parent' => array(
      'type' => 'none',
      'title' => 'Farm',
      'name' => 'management',
      'weight' => '-100',
    ),
  );
  $page->arguments = array();
  $page->conf = array(
    'admin_paths' => FALSE,
  );
  $page->default_handlers = array();
  $handler = new stdClass();
  $handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
  $handler->api_version = 1;
  $handler->name = 'page_farm_admin_panel_context';
  $handler->task = 'page';
  $handler->subtask = 'farm_admin';
  $handler->handler = 'panel_context';
  $handler->weight = 0;
  $handler->conf = array(
    'title' => 'Panel',
    'no_blocks' => 0,
    'pipeline' => 'standard',
    'body_classes_to_remove' => '',
    'body_classes_to_add' => '',
    'css_id' => '',
    'css' => '',
    'contexts' => array(),
    'relationships' => array(),
  );
  $display = new panels_display();
  $display->layout = 'twocol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'center' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = 'Farm Plan';
  $display->uuid = 'b46e42eb-9d6a-46b4-b14e-217133e33208';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-9005e75c-7c1f-4411-a745-163fc124dabd';
    $pane->panel = 'left';
    $pane->type = 'views_panes';
    $pane->subtype = 'farm_areas-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array();
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '9005e75c-7c1f-4411-a745-163fc124dabd';
    $display->content['new-9005e75c-7c1f-4411-a745-163fc124dabd'] = $pane;
    $display->panels['left'][0] = 'new-9005e75c-7c1f-4411-a745-163fc124dabd';
    $pane = new stdClass();
    $pane->pid = 'new-3f0d901e-5538-45b5-8cf1-2e190c67e079';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'log_plan-panel_pane_1';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'path' => 'log/plan',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '3f0d901e-5538-45b5-8cf1-2e190c67e079';
    $display->content['new-3f0d901e-5538-45b5-8cf1-2e190c67e079'] = $pane;
    $display->panels['right'][0] = 'new-3f0d901e-5538-45b5-8cf1-2e190c67e079';
    $pane = new stdClass();
    $pane->pid = 'new-8815b7ae-ed68-45a0-ad9f-26a01e736486';
    $pane->panel = 'right';
    $pane->type = 'views_panes';
    $pane->subtype = 'log_plan-panel_pane_2';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'path' => 'log/plan',
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 1;
    $pane->locks = array();
    $pane->uuid = '8815b7ae-ed68-45a0-ad9f-26a01e736486';
    $display->content['new-8815b7ae-ed68-45a0-ad9f-26a01e736486'] = $pane;
    $display->panels['right'][1] = 'new-8815b7ae-ed68-45a0-ad9f-26a01e736486';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['farm_admin'] = $page;

  return $pages;

}
