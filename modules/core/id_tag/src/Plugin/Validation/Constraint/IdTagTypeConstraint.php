<?php

namespace Drupal\farm_id_tag\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that ID tag type is valid.
 *
 * @Constraint(
 *   id = "IdTagType",
 *   label = @Translation("Valid ID tag type", context = "Validation"),
 * )
 */
class IdTagTypeConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'Invalid ID tag type: @type';

}
