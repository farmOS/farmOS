<?php

namespace Drupal\farm_birth\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that only one birth log references an asset.
 *
 * @Constraint(
 *   id = "UniqueBirthLog",
 *   label = @Translation("Unique birth log", context = "Validation"),
 * )
 */
class UniqueBirthLogConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = '%child already has a birth log. More than one birth log cannot reference the same child.';

}
