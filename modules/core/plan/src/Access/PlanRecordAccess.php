<?php

namespace Drupal\plan\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines plan_record access logic.
 */
class PlanRecordAccess extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // If a plan is referenced, access is based on access to the plan.
    /** @var \Drupal\plan\Entity\PlanRecordInterface $plan */
    if ($plan = $entity->getPlan()) {
      return AccessResult::allowedIf($plan->access($operation, $account));
    }

    // Otherwise, delegate to the parent method.
    return parent::checkAccess($entity, $operation, $account);
  }

}
