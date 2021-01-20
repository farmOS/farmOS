<?php

namespace Drupal\farm_location\Field;

use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computes the current location value for assets.
 */
class AssetLocationItemList extends EntityReferenceFieldItemList {

  use ComputedItemListTrait;

  /**
   * Computes the current location value for the asset.
   */
  protected function computeValue() {

    // Get the asset entity.
    $entity = $this->getEntity();

    // Get the asset's current locations.
    $locations = \Drupal::service('asset.location')->getLocation($entity);

    // Update the assets current location values to match.
    // @todo Cache this field computation.
    foreach ($locations as $delta => $location) {
      if (!empty($location->id())) {
        $value = ['target_id' => $location->id()];
        $this->list[$delta] = $this->createItem($delta, $value);
      }
    }
  }

}
