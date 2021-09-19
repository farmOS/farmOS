<?php

namespace Drupal\asset;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the controller class for assets.
 *
 * This extends the base storage class, adding required special handling for
 * asset entities.
 */
class AssetStorage extends SqlContentEntityStorage {

  /**
   * {@inheritdoc}
   */
  protected function doPreSave(EntityInterface $entity) {
    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    parent::doPreSave($entity);

    // If there is no original entity, bail.
    if (empty($entity->original)) {
      return;
    }

    // Load new and original states.
    $new_state = $entity->get('status')->first()->getString();
    $old_state = $entity->original->get('status')->first()->getString();

    $state_unchanged = $new_state == $old_state;

    // If the entity is not archived and this would otherwise not be a state
    // transition but the archive timestamp is set, then transition to the
    // archived state.
    if ($state_unchanged && $old_state != 'archived' && $entity->getArchivedTime() != NULL) {
      $entity->get('status')->first()->applyTransitionById('archive');
    }

    // If the entity is archived and this would otherwise not be a state
    // transition but the archive timestemp is NULL, then transition to the
    // active state.
    if ($state_unchanged && $old_state == 'archived' && $entity->getArchivedTime() == NULL) {
      $entity->get('status')->first()->applyTransitionById('to_active');
    }

    // If the state has not changed, bail.
    if ($state_unchanged) {
      return;
    }

    // If the state has changed to archived and no archived timestamp was
    // specified, set it to the current time.
    if ($new_state == 'archived' && $entity->getArchivedTime() == NULL) {
      $entity->setArchivedTime(\Drupal::time()->getRequestTime());
    }

    // Or, if the state has changed from archived, set a null value.
    elseif ($old_state == 'archived') {
      $entity->setArchivedTime(NULL);
    }
  }

}
