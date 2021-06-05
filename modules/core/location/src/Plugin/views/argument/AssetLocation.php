<?php

namespace Drupal\farm_location\Plugin\views\argument;

use Drupal\views\Plugin\views\argument\ArgumentPluginBase;

/**
 * An argument for filtering assets by their current location.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("asset_location")
 */
class AssetLocation extends ArgumentPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query($group_by = FALSE) {

    // First query for a list of asset IDs in the location, then use this list
    // to filter the current View.
    // We do this in two separate queries for a few reasons:
    // 1. The Drupal and Views query APIs do not support the kind of compound
    // JOIN that we use in the asset.location service's getAssetsByLocation().
    // 2. We need to allow other modules to override the asset.location service
    // to provide their own location logic (eg: the Group asset module). They
    // shouldn't have to override this Views argument handler as well.
    // 3. It keeps this Views argument handler's query modifications very
    // simple. It only needs the condition: "WHERE asset.id IN (:asset_ids)".
    // See https://www.drupal.org/project/farm/issues/3217168 for more info.
    $location = \Drupal::entityTypeManager()->getStorage('asset')->load($this->argument);
    $assets = \Drupal::service('asset.location')->getAssetsByLocation($location);
    $asset_ids = [];
    foreach ($assets as $asset) {
      $asset_ids[] = $asset->id();
    }

    // Filter to only include assets with those IDs.
    $this->ensureMyTable();
    $this->query->addWhere(0, "$this->tableAlias.id", $asset_ids, 'IN');
  }

}
