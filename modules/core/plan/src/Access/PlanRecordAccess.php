<?php

declare(strict_types=1);

namespace Drupal\plan\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\plan\Entity\PlanRecordInterface;

/**
 * Defines plan_record access logic.
 */
class PlanRecordAccess extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // If a plan is referenced, access is based on access to the plan.
    if ($entity instanceof PlanRecordInterface && $plan = $entity->getPlan()) {
      // Request the access result as an object ($return_as_object = TRUE) and
      // depend on it, so the plan access handler's cacheability propagates to
      // the plan_record (e.g. the user.permissions context, plus any membership
      // or group dependencies a contrib access handler adds). The bool form
      // would discard it, leaving the record grant cached without the
      // dependencies that should invalidate it. Allowed/neutral semantics are
      // unchanged. See https://github.com/farmOS/farmOS/issues/1089.
      $plan_access = $plan->access($operation, $account, TRUE);
      return AccessResult::allowedIf($plan_access->isAllowed())->addCacheableDependency($plan_access);
    }

    // Otherwise, delegate to the parent method.
    return parent::checkAccess($entity, $operation, $account);
  }

}
