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
    /** @var \Drupal\Core\Field\FieldItemListInterface[] $value */
    /** @var \Drupal\farm_birth\Plugin\Validation\Constraint\UniqueBirthLogConstraint $constraint */
    foreach ($value as $item) {

      // Get the referenced asset ID.
      $item_value = $item->getValue();
      $asset_id = $item_value['target_id'] ?? FALSE;

      // If there is no asset, skip.
      if (empty($asset_id)) {
        continue;
      }

      // Perform an entity query to find logs that reference the asset.
      // We do not check access to ensure that all matching logs are found.
      $query = $this->entityTypeManager->getStorage('log')->getQuery()
        ->accessCheck(FALSE)
        ->condition('type', 'birth')
        ->condition('asset', $asset_id);
      $ids = $query->execute();

      // If more than 0 birth logs reference the asset, add a violation.
      if (count($ids) > 0) {
        $asset = $this->entityTypeManager->getStorage('asset')->load($asset_id);
        $this->context->addViolation($constraint->message, ['%child' => $asset->label()]);
      }
    }
  }

}
