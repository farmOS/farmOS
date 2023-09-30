<?php

namespace Drupal\farm_quick_group\Plugin\Action;

use Drupal\farm_quick\Plugin\Action\QuickFormActionBase;

/**
 * Action for recording group membership assignment.
 *
 * @Action(
 *   id = "quick_group",
 *   label = @Translation("Assign group membership"),
 *   type = "asset",
 *   confirm_form_route_name = "farm.quick.group"
 * )
 */
class Group extends QuickFormActionBase {

  /**
   * {@inheritdoc}
   */
  public function getQuickFormId(): string {
    return 'group';
  }

}
