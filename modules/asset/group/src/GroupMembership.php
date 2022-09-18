<?php

namespace Drupal\farm_group;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Asset group membership logic.
 */
class GroupMembership implements GroupMembershipInterface {

  /**
   * The name of the log group reference field.
   *
   * @var string
   */
  const LOG_FIELD_GROUP = 'group';

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
   * @param \Drupal\farm_log\LogQueryFactoryInterface $log_query_factory
   *   Log query factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time, Connection $database) {
    $this->logQueryFactory = $log_query_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function hasGroup(AssetInterface $asset, $timestamp = NULL): bool {

    // Load the group assignment log. Bail if empty.
    $log = $this->getGroupAssignmentLog($asset, $timestamp);
    if (empty($log)) {
      return FALSE;
    }

    // Return emptiness of the group references.
    return !$log->get(static::LOG_FIELD_GROUP)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup(AssetInterface $asset, $timestamp = NULL): array {

    // Load the group assignment log. Bail if empty.
    $log = $this->getGroupAssignmentLog($asset, $timestamp);
    if (empty($log)) {
      return [];
    }

    // Return referenced entities.
    return $log->{static::LOG_FIELD_GROUP}->referencedEntities() ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupAssignmentLog(AssetInterface $asset, $timestamp = NULL): ?LogInterface {

    // If the asset is new, no group assignment logs will reference it.
    if ($asset->isNew()) {
      return NULL;
    }

    // If $timestamp is NULL, use the current time.
    if (is_null($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Query for group assignment logs that reference the asset.
    // We do not check access on the logs to ensure that none are filtered out.
    $options = [
      'asset' => $asset,
      'timestamp' => $timestamp,
      'status' => 'done',
      'limit' => 1,
    ];
    $query = $this->logQueryFactory->getQuery($options);
    $query->condition('is_group_assignment', TRUE);
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
  public function getGroupMembers(array $groups, bool $recurse = TRUE, $timestamp = NULL): array {

    // Get group ids.
    $group_ids = array_map(function (AssetInterface $group) {
      return $group->id();
    }, $groups);

    // Bail if there are no group ids.
    if (empty($group_ids)) {
      return [];
    }

    // If $timestamp is NULL, use the current time.
    if (is_null($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Build query for group members.
    $query = "
      -- Select asset IDs from the asset base table.
      SELECT a.id
      FROM {asset} a

      -- Inner join logs that reference the assets.
      INNER JOIN {asset_field_data} afd ON afd.id = a.id
      INNER JOIN {log__asset} la ON a.id = la.asset_target_id AND la.deleted = 0
      INNER JOIN {log_field_data} lfd ON lfd.id = la.entity_id

      -- Inner join group assets referenced by the logs.
      INNER JOIN {log__group} lg ON lg.entity_id = lfd.id AND lg.deleted = 0

      -- Left join ANY future group assignment logs for the same asset.
      -- In the WHERE clause we'll exclude all records that have future logs,
      -- leaving only the 'current' log entry.
      LEFT JOIN (
          {log_field_data} lfd2
          INNER JOIN {log__asset} la2 ON la2.entity_id = lfd2.id AND la2.deleted = 0
          ) ON lfd2.is_group_assignment = 1 AND la2.asset_target_id = a.id

          -- Future log entries have either a higher timestamp, or an equal timestamp and higher log ID.
          AND (lfd2.timestamp > lfd.timestamp OR (lfd2.timestamp = lfd.timestamp AND lfd2.id > lfd.id))

          -- Don't include future logs beyond the given timestamp.
          -- These conditions should match the values in the WHERE clause.
          AND (lfd2.status = 'done') AND (lfd2.timestamp <= :timestamp)

      -- Limit results to completed membership assignment logs to the desired
      -- group that took place before the given timestamp.
      WHERE (lfd.is_group_assignment = 1) AND (lfd.status = 'done') AND (lfd.timestamp <= :timestamp) AND (lg.group_target_id IN (:group_ids[]))

      -- Exclude records with future log entries.
      AND lfd2.id IS NULL";
    $args = [
      ':timestamp' => $timestamp,
      ':group_ids[]' => $group_ids,
    ];
    $result = $this->database->query($query, $args)->fetchAll();
    $asset_ids = [];
    foreach ($result as $row) {
      if (!empty($row->id)) {
        $asset_ids[] = $row->id;
      }
    }
    if (empty($asset_ids)) {
      return [];
    }
    $asset_ids = array_unique($asset_ids);
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $this->entityTypeManager->getStorage('asset')->loadMultiple($asset_ids);
    if ($recurse) {
      // Iterate through the assets to check if any of them are groups.
      $groups = array_filter($assets, function (AssetInterface $asset) {
        return $asset->bundle() === 'group';
      });
      // Use array_replace so that numeric keys are preserved.
      $assets = array_replace($assets, $this->getGroupMembers($groups, $recurse, $timestamp));
    }
    return $assets;
  }

}
