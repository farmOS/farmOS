<?php

namespace Drupal\Tests\farm_asset\Traits;

use Drupal\farm_asset\Entity\FarmAsset;

/**
 * Provides methods to create farm_asset entities.
 *
 * This trait is meant to be used only by test classes.
 */
trait FarmAssetCreationTrait {

  /**
   * Creates a farm_asset entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\farm_asset\Entity\FarmAssetInterface
   *   The farm_asset entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createFarmAssetEntity(array $values = []) {
    /** @var \Drupal\farm_asset\Entity\FarmAssetInterface $entity */
    $entity = FarmAsset::create($values + [
      'name' => $this->randomMachineName(),
      'type' => 'default',
    ]);
    $entity->save();
    return $entity;
  }

}
