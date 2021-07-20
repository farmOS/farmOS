<?php

namespace Drupal\farm_quick\Traits;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides methods for working with assets.
 */
trait QuickAssetTrait {

  use MessengerTrait;
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

    // Display a message with a link to the asset.
    $message = $this->t('Asset created: <a href=":url">@name</a>', [':url' => $asset->toUrl()->toString(), '@name' => $asset->label()]);
    $this->messenger->addStatus($message);

    // Return the asset entity.
    return $asset;
  }

}
