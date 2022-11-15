<?php

/**
 * @file
 * Update hooks for farm_log_category.module.
 */

use Symfony\Component\Yaml\Yaml;

/**
 * Create log categorize action.
 */
function farm_log_category_post_update_create_log_categorize_action(&$sandbox = NULL) {
  $action_id = 'log_categorize_action';
  $config_path = \Drupal::service('extension.list.module')->getPath('farm_log_category') . "/config/optional/system.action.$action_id.yml";
  $data = Yaml::parseFile($config_path);
  \Drupal::configFactory()
    ->getEditable("system.action.$action_id")
    ->setData($data)
    ->save(TRUE);
}
