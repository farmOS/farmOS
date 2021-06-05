<?php

namespace Drupal\farm_group\Plugin\views\argument;

use Drupal\views\Plugin\views\argument\ArgumentPluginBase;

/**
 * An argument for filtering assets by their current group.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("asset_group")
 */
class AssetGroup extends ArgumentPluginBase {

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\farm_location\Plugin\views\argument\AssetLocation
   */
  public function query($group_by = FALSE) {

    // First query for a list of asset IDs in the group, then use this list to
    // filter the current View.
    // We do this in two separate queries for a few reasons:
    // 1. The Drupal and Views query APIs do not support the kind of compound
    // JOIN that we use in the group.membership service's getMembers().
    // 2. We need to allow other modules to override the group.membership
    // service to provide their own location logic. They shouldn't have to
    // override this Views argument handler as well.
    // 3. It keeps this Views argument handler's query modifications very
    // simple. It only needs the condition: "WHERE asset.id IN (:asset_ids)".
    // See https://www.drupal.org/project/farm/issues/3217184 for more info.
    $group = \Drupal::entityTypeManager()->getStorage('asset')->load($this->argument);
    $assets = \Drupal::service('group.membership')->getGroupMembers($group);
    $asset_ids = [];
    foreach ($assets as $asset) {
      $asset_ids[] = $asset->id();
    }

    // If there are no asset IDs, add 0 to ensure the array is not empty.
    if (empty($asset_ids)) {
      $asset_ids[] = 0;
    }

    // Filter to only include assets with those IDs.
    $this->ensureMyTable();
    $this->query->addWhere(0, "$this->tableAlias.id", $asset_ids, 'IN');
  }

}
