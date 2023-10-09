<?php

namespace Drupal\farm_id_tag\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the IdTagTypeConstraint constraint.
 */
class IdTagTypeConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (empty($value->type)) {
      return;
    }
    $bundle = $value->getEntity()->bundle();
    $valid_types = array_keys(farm_id_tag_type_options($bundle));
    if (!in_array($value->type, $valid_types)) {
      $this->context->addViolation($constraint->message, ['@type' => $value->type]);
    }
  }

}
