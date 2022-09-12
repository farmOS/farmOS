<?php

namespace Drupal\farm_birth\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the UniqueBirthLog constraint.
 */
class UniqueBirthLogConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a UniqueBirthLogConstraintValidator object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $value */
    /** @var \Drupal\farm_birth\Plugin\Validation\Constraint\UniqueBirthLogConstraint $constraint */

    // Only continue if this is a birth log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $value->getParent()->getValue();
    if (!is_null($log) && $log->bundle() != 'birth') {
      return;
    }

    // Iterate through referenced entities.
    foreach ($value->referencedEntities() as $delta => $asset) {

      // If the log is not new, skip validation.
      // A birth log exits so there is no need to check if one can be created.
      /** @var \Drupal\log\Entity\LogInterface $log */
      $log = $value->getParent()->getValue();
      if (!$log->isNew()) {
        return;
      }

      // Query the number of birth logs that reference the asset.
      // We do not check access to ensure that all matching logs are found.
      $count = $this->entityTypeManager->getStorage('log')->getAggregateQuery()
        ->accessCheck(FALSE)
        ->condition('type', 'birth')
        ->condition('asset', $asset->id())
        ->count()
        ->execute();

      // If more than 0 birth logs reference the asset, add a violation.
      if ($count > 0) {
        $this->context->buildViolation($constraint->message, ['%child' => $asset->label()])
          ->atPath((string) $delta . '.target_id')
          ->setInvalidValue($asset->id())
          ->addViolation();
      }
    }
  }

}
