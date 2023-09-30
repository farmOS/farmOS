<?php

namespace Drupal\farm_quick_movement\Plugin\Action;

use Drupal\farm_quick\Plugin\Action\QuickFormActionBase;

/**
 * Action for recording movements.
 *
 * @Action(
 *   id = "quick_movement",
 *   label = @Translation("Record movement"),
 *   type = "asset",
 *   confirm_form_route_name = "farm.quick.movement"
 * )
 */
class Movement extends QuickFormActionBase {

  /**
   * {@inheritdoc}
   */
  public function getQuickFormId(): string {
    return 'movement';
  }

}
