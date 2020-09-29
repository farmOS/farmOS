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

    // If the state has not changed, bail.
    if ($new_state == $old_state) {
      return;
    }

    // If the state has changed to archived, save the archived timestamp.
    if ($new_state == 'archived') {
      $entity->setArchivedTime(\Drupal::time()->getRequestTime());
    }

    // Or, if the state has changed from archived, set a null value.
    elseif ($old_state == 'archived') {
      $entity->setArchivedTime(NULL);
    }
  }

}
