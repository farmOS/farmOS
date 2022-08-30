<?php

namespace Drupal\farm_location;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Asset location logic.
 */
class AssetLocation implements AssetLocationInterface {

  /**
   * The name of the asset intrinsic geometry field.
   *
   * @var string
   */
  const ASSET_FIELD_GEOMETRY = 'intrinsic_geometry';

  /**
   * The name of the asset boolean location field.
   *
   * @var string
   */
  const ASSET_FIELD_LOCATION = 'is_location';

  /**
   * The name of the asset boolean fixed field.
   *
   * @var string
   */
  const ASSET_FIELD_FIXED = 'is_fixed';

  /**
   * Log location service.
   *
   * @var \Drupal\farm_location\LogLocationInterface
   */
  protected LogLocationInterface $logLocation;

  /**
   * Log query factory.
   *
   * @var \Drupal\farm_log\LogQueryFactoryInterface
   */
  protected LogQueryFactoryInterface $logQueryFactory;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Class constructor.
   *
   * @param \Drupal\farm_location\LogLocationInterface $log_location
   *   Log location service.
   * @param \Drupal\farm_log\LogQueryFactoryInterface $log_query_factory
   *   Log query factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(LogLocationInterface $log_location, LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time, Connection $database) {
    $this->logLocation = $log_location;
    $this->logQueryFactory = $log_query_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function isLocation(AssetInterface $asset): bool {
    return !empty($asset->{static::ASSET_FIELD_LOCATION}->value);
  }

  /**
   * {@inheritdoc}
   */
  public function isFixed(AssetInterface $asset): bool {
    return !empty($asset->{static::ASSET_FIELD_FIXED}->value);
  }

  /**
   * {@inheritdoc}
   */
  public function hasLocation(AssetInterface $asset, $timestamp = NULL): bool {

    // If the asset is fixed, always return FALSE.
    if ($this->isFixed($asset)) {
      return FALSE;
    }

    // Load the movement log. Bail if empty.
    $log = $this->getMovementLog($asset, $timestamp);
    if (empty($log)) {
      return FALSE;
    }

    // Return whether the log has a location.
    return $this->logLocation->hasLocation($log);
  }

  /**
   * {@inheritdoc}
   */
  public function hasGeometry(AssetInterface $asset, $timestamp = NULL): bool {

    // If the asset is fixed, return emptiness of its intrinsic geometry.
    if ($this->isFixed($asset)) {
      return !$asset->get(static::ASSET_FIELD_GEOMETRY)->isEmpty();
    }

    // Load the movement log. Bail if empty.
    $log = $this->getMovementLog($asset, $timestamp);
    if (empty($log)) {
      return FALSE;
    }

    // Return emptiness of the log's location reference.
    return $this->logLocation->hasGeometry($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation(AssetInterface $asset, $timestamp = NULL): array {

    // If the asset is fixed, return empty array.
    if ($this->isFixed($asset)) {
      return [];
    }

    // Load the movement log. Bail if empty.
    $log = $this->getMovementLog($asset, $timestamp);
    if (empty($log)) {
      return [];
    }

    // Return locations referenced by the log.
    return $this->logLocation->getLocation($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getGeometry(AssetInterface $asset, $timestamp = NULL): string {

    // If the asset is fixed, return its intrinsic geometry.
    if ($this->isFixed($asset)) {
      return $asset->get(static::ASSET_FIELD_GEOMETRY)->value ?? '';
    }

    // Load the movement log. Bail if empty.
    $log = $this->getMovementLog($asset, $timestamp);
    if (empty($log)) {
      return '';
    }

    // Return the log's geometry.
    return $this->logLocation->getGeometry($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getMovementLog(AssetInterface $asset, $timestamp = NULL): ?LogInterface {

    // If the asset is new, no movement logs will reference it.
    if ($asset->isNew()) {
      return NULL;
    }

    // If $timestamp is NULL, use the current time.
    if (is_null($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Query for movement logs that reference the asset.
    // We do not check access on the logs to ensure that none are filtered out.
    $options = [
      'asset' => $asset,
      'timestamp' => $timestamp,
      'status' => 'done',
      'limit' => 1,
    ];
    $query = $this->logQueryFactory->getQuery($options);
    $query->condition('is_movement', TRUE);
    $query->accessCheck(FALSE);
    $log_ids = $query->execute();

    // Bail if no logs are found.
    if (empty($log_ids)) {
      return NULL;
    }

    // Load the first log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $this->entityTypeManager->getStorage('log')->load(reset($log_ids));

    // Return the log, if available.
    if (!is_null($log)) {
      return $log;
    }

    // Otherwise, return NULL.
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setIntrinsicGeometry(AssetInterface $asset, string $wkt): void {
    $asset->{static::ASSET_FIELD_GEOMETRY} = $wkt;
  }

  /**
   * {@inheritdoc}
   */
  public function getAssetsByLocation(array $locations, $timestamp = NULL): array {

    // This should never limit itself to assets with `is_location` property
    // set to TRUE, so that we can always prevent circular asset location.
    // @see Drupal\farm_location\Plugin\Validation\Constraint\CircularAssetLocationConstraintValidator

    // Get location ids.
    $location_ids = array_map(function (AssetInterface $location) {
      return $location->id();
    }, $locations);

    // Bail if there are no location ids.
    if (empty($location_ids)) {
      return [];
    }

    // If $timestamp is NULL, use the current time.
    if (is_null($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Build query for assets in locations.
    $query = "
      -- Select asset IDs from the asset base table.
      SELECT DISTINCT a.id
      FROM {asset} a

      -- Inner join logs that reference the assets.
      INNER JOIN {asset_field_data} afd ON afd.id = a.id
      INNER JOIN {log__asset} la ON a.id = la.asset_target_id AND la.deleted = 0
      INNER JOIN {log_field_data} lfd ON lfd.id = la.entity_id

      -- Inner join location assets referenced by the logs.
      INNER JOIN {log__location} ll ON ll.entity_id = lfd.id AND ll.deleted = 0

      -- Left join ANY future movement logs for the same asset.
      -- In the WHERE clause we'll exclude all records that have future logs,
      -- leaving only the 'current' log entry.
      LEFT JOIN (
          {log_field_data} lfd2
          INNER JOIN {log__asset} la2 ON la2.entity_id = lfd2.id AND la2.deleted = 0
          ) ON lfd2.is_movement = 1 AND la2.asset_target_id = a.id

          -- Future log entries have either a higher timestamp, or an equal timestamp and higher log ID.
          AND (lfd2.timestamp > lfd.timestamp OR (lfd2.timestamp = lfd.timestamp AND lfd2.id > lfd.id))

          -- Don't include future logs beyond the given timestamp.
          -- These conditions should match the values in the WHERE clause.
          AND (lfd2.status = 'done') AND (lfd2.timestamp <= :timestamp)

      -- Limit results to completed movement logs to the desired location that
      -- took place before the given timestamp.
      WHERE (lfd.is_movement = 1) AND (lfd.status = 'done') AND (lfd.timestamp <= :timestamp) AND (ll.location_target_id IN (:location_ids[]))

      -- Exclude records with future log entries.
      AND lfd2.id IS NULL";
    $args = [
      ':timestamp' => $timestamp,
      ':location_ids[]' => $location_ids,
    ];
    $asset_ids = $this->database->query($query, $args)->fetchCol();
    return $this->entityTypeManager->getStorage('asset')->loadMultiple($asset_ids);
  }

}
