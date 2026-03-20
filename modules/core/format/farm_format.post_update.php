<?php

/**
 * @file
 * Post update hooks for the farm_format module.
 */

declare(strict_types=1);

/**
 * Install the Markdown module.
 */
function farm_format_post_update_install_markdown(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('markdown')) {
    \Drupal::service('module_installer')->install(['markdown']);
  }
}
