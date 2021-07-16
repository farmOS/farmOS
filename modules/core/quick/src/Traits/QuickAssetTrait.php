<?php

namespace Drupal\farm_quick\Traits;

use Drupal\asset\Entity\Asset;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides methods for working with assets.
 */
trait QuickAssetTrait {

  use StringTranslationTrait;

  /**
   * Create an asset.
   *
   * @param array $values
   *   An array of values to initialize the asset with.
   *
   * @return \Drupal\asset\Entity\AssetInterface
   *   The asset entity that was created.
   */
  public function createAsset(array $values = []) {

    // Start a new asset entity with the provided values.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create($values);

    // Save the asset.
    $asset->save();

    // Return the asset entity.
    return $asset;
  }

}
