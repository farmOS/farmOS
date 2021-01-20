<?php

namespace Drupal\farm_location\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computes the current geometry value for assets.
 */
class AssetGeometryItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * Computes the current geometry value for the asset.
   */
  protected function computeValue() {

    // Get the asset entity.
    $entity = $this->getEntity();

    // Get the asset geometry.
    $geometry = \Drupal::service('asset.location')->getGeometry($entity);

    // Update the assets current geometry value to match.
    // @todo Cache this field computation.
    $this->list[0] = $this->createItem(0, $geometry);
  }

}
