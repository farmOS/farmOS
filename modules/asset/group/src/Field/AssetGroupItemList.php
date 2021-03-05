<?php

namespace Drupal\farm_group\Field;

use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computes the current group value for assets.
 */
class AssetGroupItemList extends EntityReferenceFieldItemList {

  use ComputedItemListTrait;

  /**
   * Computes the current group value for the asset.
   */
  protected function computeValue() {

    // Get the asset entity.
    $entity = $this->getEntity();

    // Get the asset's current groups.
    $groups = \Drupal::service('group.membership')->getGroup($entity);

    // Update the assets current group values to match.
    // @todo Cache this field computation.
    foreach ($groups as $delta => $group) {
      if (!empty($group->id())) {
        $value = ['target_id' => $group->id()];
        $this->list[$delta] = $this->createItem($delta, $value);
      }
    }
  }

}
