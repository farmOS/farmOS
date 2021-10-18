<?php

namespace Drupal\plan\Plugin\Action;

/**
 * Action that marks a plan as active.
 *
 * @Action(
 *   id = "plan_activate_action",
 *   label = @Translation("Makes a Plan active"),
 *   type = "plan"
 * )
 */
class PlanActivate extends PlanStateChangeBase {

  /**
   * {@inheritdoc}
   */
  protected $targetState = 'active';

}
