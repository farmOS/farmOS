<?php

namespace Drupal\farm_ui_user\Controller;

use Drupal\system\Controller\Http4xxController;

/**
 * Provides a controller for error pages.
 */
class ErrorPageController extends Http4xxController {

  /**
   * {@inheritdoc}
   */
  public function on403() {
    $output = parent::on403();

    /** @var \Drupal\block\Entity\Block $block */
    $block = $this->entityTypeManager()->getStorage('block')->load('userlogin');

    // Build the blocks renderable output.
    $output['form'] = $this->entityTypeManager()->getViewBuilder('block')->view($block);

    return $output;
  }

}
