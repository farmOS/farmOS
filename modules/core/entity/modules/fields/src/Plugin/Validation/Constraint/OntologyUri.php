<?php

namespace Drupal\farm_entity_fields\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraints\Url;

/**
 * Checks that URI is valid.
 *
 * @Constraint(
 *   id = "OntologyUri",
 *   label = @Translation("Valid URI", context = "Validation"),
 * )
 */
class OntologyUri extends Url {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'This value is not a valid URI.';

}
