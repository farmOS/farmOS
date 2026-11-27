<?php

declare(strict_types=1);

namespace Drupal\farm_owner\Hook;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Form hook implementations for farm_owner.
 */
class FormHooks {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountInterface $currentUser,
    protected RequestStack $requestStack,
  ) {}

  /**
   * Implements hook_entity_prepare_form().
   */
  #[Hook('entity_prepare_form')]
  public function entityPrepareForm(EntityInterface $entity, $operation, FormStateInterface $form_state) {

    // Only prepopulate new logs, preserving explicit owners and edit forms.
    if ($operation !== 'add' || !$entity->isNew() || !$entity instanceof LogInterface || !$entity->hasField('owner')) {
      return;
    }
    $owner_field = $entity->get('owner');
    if (!$owner_field->isEmpty() || !$owner_field->access('edit', $this->currentUser)) {
      return;
    }

    $query = $this->requestStack->getCurrentRequest()->query;
    if (!$query->has('owner')) {
      return;
    }

    // Accept a single owner or an array, without assuming query cardinality.
    $owner_ids = array_filter((array) $query->all()['owner'], static function ($id) {
      return is_scalar($id) && ctype_digit((string) $id) && (int) $id > 0;
    });
    $owners = $this->entityTypeManager->getStorage('user')->loadMultiple($owner_ids);
    foreach ($owners as $owner) {
      if ($owner->access('view', $this->currentUser)) {
        $owner_field->appendItem($owner);
      }
    }
  }

}
