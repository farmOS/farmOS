<?php

namespace Drupal\farm_entity_fields\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\UrlValidator;

/**
 * Validates the OntologyUri constraint.
 */
class OntologyUriValidator extends UrlValidator {

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, Constraint $constraint) {
    parent::validate($value->value, $constraint);
  }

}
