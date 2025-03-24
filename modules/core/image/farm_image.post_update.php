<?php

/**
 * @file
 * Updates farm_image module.
 */

declare(strict_types=1);

/**
 * Install and configure the imagick module, if the PHP extension is available.
 */
function farm_image_post_update_install_imagick() {
  if (extension_loaded('imagick')) {

    // Install the imagick module.
    if (!\Drupal::service('module_handler')->moduleExists('imagick')) {
      \Drupal::service('module_installer')->install(['imagick']);
    }

    // Make imagick the default image toolkit.
    $config = \Drupal::configFactory()->getEditable('system.image');
    $config->set('toolkit', 'imagick');
    $config->save();

    // Configure imagick to disable strip_metadata.
    // This ensures that EXIF data is preserved in derivative images, which
    // allows browsers to auto-orient them if they have Orientation data.
    $config = \Drupal::configFactory()->getEditable('imagick.config');
    $config->set('strip_metadata', FALSE);
    $config->save();
  }
}
