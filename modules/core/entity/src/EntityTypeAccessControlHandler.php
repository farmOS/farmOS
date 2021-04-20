<?php

namespace Drupal\farm_entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Allow access to the view label operation.
 */
class EntityTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * Allow access to the entity type label.
   *
   * @var bool
   */
  protected $viewLabelOperation = TRUE;

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    // Allow access to the entity type label.
    if ($operation === 'view label') {
      return AccessResult::allowed();
    }

    return parent::checkAccess($entity, $operation, $account);
  }

}
