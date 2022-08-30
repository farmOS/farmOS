<?php

namespace Drupal\farm_location\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that a log is not creating a circular asset location.
 *
 * @Constraint(
 *   id = "CircularAssetLocation",
 *   label = @Translation("Circular asset location", context = "Validation"),
 * )
 */
class CircularAssetLocationConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = '%asset cannot be located within itself.';

}
