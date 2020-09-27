<?php

namespace Drupal\Tests\asset\Traits;

use Drupal\asset\Entity\Asset;

/**
 * Provides methods to create asset entities.
 *
 * This trait is meant to be used only by test classes.
 */
trait AssetCreationTrait {

  /**
   * Creates an asset entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\asset\Entity\AssetInterface
   *   The asset entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createAssetEntity(array $values = []) {
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $entity = Asset::create($values + [
      'name' => $this->randomMachineName(),
      'type' => 'default',
    ]);
    $entity->save();
    return $entity;
  }

}
