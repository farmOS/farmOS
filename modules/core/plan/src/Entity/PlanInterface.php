<?php

namespace Drupal\plan\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining plan entities.
 *
 * @ingroup plan
 */
interface PlanInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, EntityOwnerInterface {

  /**
   * Gets the plan name.
   *
   * @return string
   *   The plan name.
   */
  public function getName();

  /**
   * Sets the plan name.
   *
   * @param string $name
   *   The plan name.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   The plan entity.
   */
  public function setName($name);

  /**
   * Gets the plan creation timestamp.
   *
   * @return int
   *   Creation timestamp of the plan.
   */
  public function getCreatedTime();

  /**
   * Sets the plan creation timestamp.
   *
   * @param int $timestamp
   *   Creation timestamp of the plan.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   The plan entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the plan archived timestamp.
   *
   * @return int
   *   Archived timestamp of the plan.
   */
  public function getArchivedTime();

  /**
   * Sets the plan archived timestamp.
   *
   * @param int $timestamp
   *   Archived timestamp of the plan.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   The plan entity.
   */
  public function setArchivedTime($timestamp);

  /**
   * Gets the label of the the plan type.
   *
   * @return string
   *   The label of the plan type.
   */
  public function getBundleLabel();

}
