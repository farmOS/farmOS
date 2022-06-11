<?php

namespace Drupal\farm_group\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\farm_group\GroupMembershipInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the CircularGroupMembership constraint.
 */
class CircularGroupMembershipConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

  /**
   * CircularGroupMembershipConstraintValidator constructor.
   *
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   Group membership service.
   */
  public function __construct(GroupMembershipInterface $group_membership) {
    $this->groupMembership = $group_membership;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('group.membership'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $value */
    /** @var \Drupal\farm_group\Plugin\Validation\Constraint\CircularGroupMembershipConstraint $constraint */

    // Get the log that this field is on.
    $log = $value->getParent()->getValue();

    // If the log is not a group assignment, we have nothing to validate.
    if (empty($log->get('is_group_assignment')->value)) {
      return;
    }

    // Get the group(s) that asset(s) are being made members of.
    $groups = $log->get('group')->referencedEntities();

    // If there are no groups, we have nothing to validate.
    if (empty($groups)) {
      return;
    }

    // Iterate through referenced entities.
    foreach ($value->referencedEntities() as $delta => $asset) {

      // If this asset is not a group, skip it.
      if ($asset->bundle() != 'group') {
        continue;
      }

      // Load members of this group (recursively).
      $members = $this->groupMembership->getGroupMembers([$asset], TRUE);

      // Make sure that none of the group(s) are members of this asset.
      foreach ($groups as $group) {
        foreach ($members as $member) {
          if ($group->id() == $member->id()) {
            $this->context->buildViolation($constraint->message, ['%asset' => $asset->label()])
              ->atPath((string) $delta . '.target_id')
              ->setInvalidValue($asset->id())
              ->addViolation();
          }
        }
      }
    }
  }

}
