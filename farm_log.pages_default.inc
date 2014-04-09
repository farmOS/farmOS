<?php
/**
 * @file
 * farm_log.pages_default.inc
 */

/**
 * Implements hook_default_page_manager_pages().
 */
function farm_log_default_page_manager_pages() {
  $page = new stdClass();
  $page->disabled = FALSE; /* Edit this to true to make a default page disabled initially */
  $page->api_version = 1;
  $page->name = 'farm_log_admin';
  $page->task = 'page';
  $page->admin_title = 'Farm Log Admin';
  $page->admin_description = 'Administrative list of farm logs.';
  $page->path = 'admin/farm/logs/overview';
  $page->access = array(
    'plugins' => array(
      0 => array(
        'name' => 'perm',
        'settings' => array(
          'perm' => 'view any log entities',
        ),
        'context' => 'logged-in-user',
        'not' => FALSE,
      ),
    ),
    'logic' => 'and',
  );
  $page->menu = array(
    'type' => 'default tab',
    'title' => 'Overview',
    'name' => 'management',
    'weight' => '-10',
    'parent' => array(
      'type' => 'tab',
      'title' => 'Logs',
      'name' => 'management',
      'weight' => '50',
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
  $handler->name = 'page_farm_log_admin_panel_context';
  $handler->task = 'page';
  $handler->subtask = 'farm_log_admin';
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
  $display->layout = 'onecol';
  $display->layout_settings = array();
  $display->panel_settings = array(
    'style_settings' => array(
      'default' => NULL,
      'middle' => NULL,
      'left' => NULL,
      'right' => NULL,
    ),
  );
  $display->cache = array();
  $display->title = 'Farm Logs';
  $display->uuid = '89dbde73-0b2b-4159-9a2c-221cb1cc93f3';
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
    $pane->pid = 'new-792e29de-780a-478c-a9dc-dc087dcc24bb';
    $pane->panel = 'middle';
    $pane->type = 'custom';
    $pane->subtype = 'custom';
    $pane->shown = TRUE;
    $pane->access = array();
    $pane->configuration = array(
      'admin_title' => 'Description',
      'title' => '',
      'body' => 'Navigate to the log you want using the tabs above.',
      'format' => 'plain_text',
      'substitute' => TRUE,
    );
    $pane->cache = array();
    $pane->style = array(
      'settings' => NULL,
    );
    $pane->css = array();
    $pane->extras = array();
    $pane->position = 0;
    $pane->locks = array();
    $pane->uuid = '792e29de-780a-478c-a9dc-dc087dcc24bb';
    $display->content['new-792e29de-780a-478c-a9dc-dc087dcc24bb'] = $pane;
    $display->panels['middle'][0] = 'new-792e29de-780a-478c-a9dc-dc087dcc24bb';
  $display->hide_title = PANELS_TITLE_FIXED;
  $display->title_pane = '0';
  $handler->conf['display'] = $display;
  $page->default_handlers[$handler->name] = $handler;
  $pages['farm_log_admin'] = $page;

  return $pages;

}
