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

    // If its a location, copy its intrinsic geometry.
    // @todo Should this be checking 'fixed' instead?
    if ($entity->get('location')->value) {
      $geom = $entity->get('geometry')->value ?? '';
      $this->list[0] = $this->createItem(0, $geom);
      return;
    }

    // Query logs.
    // @todo use the farm.log_query service.
    $query = \Drupal::entityTypeManager()->getStorage('log')->getQuery();

    // Add a tag.
    $query->addTag('farm.location.asset_geofield');

    // Sort by timestamp and then log ID, descending.
    $query->sort('timestamp', 'DESC');
    $query->sort('id', 'DESC');

    // Limit to logs that aren't in the future.
    $query->condition('timestamp', \Drupal::time()->getCurrentTime(), '<=');

    // Limit to logs that are done.
    $query->condition('status', 'done');

    // Limit to logs that reference the asset.
    $query->condition('asset.entity.id', $entity->id());

    // Limit to logs that are a movement.
    $query->condition('movement', TRUE);

    // Limit to the single latest log.
    $query->range(0, 1);

    $result = $query->execute();

    // Bail if no logs are found.
    if (empty($result)) {
      return;
    }

    // Get the first log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = \Drupal::entityTypeManager()->getStorage('log')->load(reset($result));

    // Bail if the log was not loaded.
    if (empty($log)) {
      return;
    }

    // Load the log.location service.
    /** @var \Drupal\farm_location\LogLocationInterface $log_location */
    $log_location = \Drupal::service('log.location');

    // Update the assets current geometry value to match.
    // @todo Invalidate/disable the entity cache for JSONAPI.
    // @todo Cache this field computation.
    $geom = $log_location->getGeometry($log);
    $this->list[0] = $this->createItem(0, $geom);
  }

}
