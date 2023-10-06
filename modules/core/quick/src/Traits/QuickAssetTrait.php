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
  use QuickStringTrait;

  /**
   * Returns the quick form ID.
   *
   * This must be implemented by the quick form class that uses this trait.
   *
   * @see \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface
   *
   * @return string
   *   The quick form ID.
   */
  abstract public function getQuickId();

  /**
   * Create an asset.
   *
   * @param array $values
   *   An array of values to initialize the asset with.
   *
   * @return \Drupal\asset\Entity\AssetInterface
   *   The asset entity that was created.
   */
  protected function createAsset(array $values = []) {

    // Trim the asset name to 255 characters.
    if (!empty($values['name'])) {
      $values['name'] = $this->trimString($values['name'], 255);
    }

    // Start a new asset entity with the provided values.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create($values);

    // Track which quick form created the entity.
    $asset->quick[] = $this->getQuickId();

    // Save the asset.
    $asset->save();

    // Display a message with a link to the asset.
    $message = $this->t('Asset created: <a href=":url">@name</a>', [':url' => $asset->toUrl()->toString(), '@name' => $asset->label()]);
    $this->messenger->addStatus($message);

    // Return the asset entity.
    return $asset;
  }

}
