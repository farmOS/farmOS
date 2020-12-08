<?php

namespace Drupal\farm_ui_user\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a controller for error pages.
 */
class ErrorPageController extends ControllerBase {

  /**
   * Returns an Access Denied page.
   */
  public function accessDenied() {

    /** @var \Drupal\block\Entity\Block $block */
    $block = $this->entityTypeManager()->getStorage('block')->load('userlogin');

    // Build the blocks renderable output.
    $output['form'] = $this->entityTypeManager()->getViewBuilder('block')->view($block);

    return $output;
  }

}
