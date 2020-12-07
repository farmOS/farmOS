<?php

namespace Drupal\plan\Plugin\Action;

use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Action that marks a plan as active.
 *
 * @Action(
 *   id = "plan_activate_action",
 *   label = @Translation("Makes a Plan active"),
 *   type = "plan"
 * )
 */
class PlanActivate extends EntityActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute(PlanInterface $plan = NULL) {
    if ($plan) {
      $plan->get('status')->first()->applyTransitionById('to_active');
      $plan->setNewRevision(TRUE);
      $plan->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\plan\Entity\PlanInterface $object */
    $result = $object->get('status')->access('edit', $account, TRUE)
      ->andIf($object->access('update', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
