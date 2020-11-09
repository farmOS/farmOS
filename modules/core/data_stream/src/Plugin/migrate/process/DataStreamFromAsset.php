<?php

namespace Drupal\data_stream\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Gets the first data stream associated with an asset.
 *
 * This is helpful in migrating data that was previously associated with
 * a sensor asset ID.
 *
 * @MigrateProcessPlugin(
 *   id = "data_stream_from_asset"
 * )
 */
class DataStreamFromAsset extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Get the asset_id.
    $asset_id = $row->getDestinationProperty('asset_id');

    // Bail if no asset ids are provided.
    if (empty($asset_id)) {
      return NULL;
    }

    // Load asset.
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset_id);

    // Return the first data stream ID if one exists.
    if (!empty($asset) && $asset->hasField('data_stream')) {
      $ids = array_column($asset->data_stream->getValue(), 'target_id');
      return reset($ids);
    }

    return NULL;
  }

}
