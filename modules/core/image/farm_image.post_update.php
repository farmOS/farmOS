<?php

/**
 * @file
 * Updates farm_image module.
 */

declare(strict_types=1);

/**
 * Install the imagick module, if the PHP extension is available.
 */
function farm_image_post_update_install_imagick() {
  if (extension_loaded('imagick')) {

    // Install the imagick module.
    if (!\Drupal::service('module_handler')->moduleExists('imagick')) {
      \Drupal::service('module_installer')->install(['imagick']);
    }
  }
}
