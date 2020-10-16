<?php

namespace Drupal\Tests\plan\Traits;

use Drupal\plan\Entity\Plan;

/**
 * Provides methods to create plan entities.
 *
 * This trait is meant to be used only by test classes.
 */
trait PlanCreationTrait {

  /**
   * Creates an plan entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   The plan entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createPlanEntity(array $values = []) {
    /** @var \Drupal\plan\Entity\PlanInterface $entity */
    $entity = Plan::create($values + [
      'name' => $this->randomMachineName(),
      'type' => 'default',
    ]);
    $entity->save();
    return $entity;
  }

}
