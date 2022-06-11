<?php

namespace Drupal\farm_group\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that a log is not creating a circular group membership.
 *
 * @Constraint(
 *   id = "CircularGroupMembership",
 *   label = @Translation("Circular group membership", context = "Validation"),
 * )
 */
class CircularGroupMembershipConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = '%asset cannot be a member of itself.';

}
