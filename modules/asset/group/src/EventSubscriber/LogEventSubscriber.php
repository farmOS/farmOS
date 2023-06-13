<?php

namespace Drupal\farm_group\EventSubscriber;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\farm_group\GroupMembershipInterface;
use Drupal\farm_location\EventSubscriber\LogEventSubscriber as LocationLogEventSubscriber;
use Drupal\log\Entity\LogInterface;
use Drupal\log\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Invalidate asset cache when group membership and group location changes.
 */
class LogEventSubscriber implements EventSubscriberInterface {

  /**
   * Cache tag invalidator service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected CacheTagsInvalidatorInterface $cacheTagsInvalidator;

  /**
   * Datetime time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

  /**
   * LogEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   Cache tag invalidator service.
   * @param \Drupal\Component\Datetime\TimeInterface $date_time
   *   Datetime time service.
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   Group membership service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cache_tags_invalidator, TimeInterface $date_time, GroupMembershipInterface $group_membership) {
    $this->time = $date_time;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
    $this->groupMembership = $group_membership;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogEvent::DELETE => 'logDelete',
      LogEvent::PRESAVE => 'logPresave',
    ];
  }

  /**
   * Perform actions on log delete.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function logDelete(LogEvent $event) {
    $this->invalidateAssetCacheOnGroupAssignment($event->log);
    $this->invalidateGroupMemberAssetCacheOnMovement($event->log);
  }

  /**
   * Perform actions on log presave.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function logPresave(LogEvent $event) {
    $this->invalidateAssetCacheOnGroupAssignment($event->log);
    $this->invalidateGroupMemberAssetCacheOnMovement($event->log);
  }

  /**
   * Invalidate asset caches when assets group membership changes.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  protected function invalidateAssetCacheOnGroupAssignment(LogInterface $log): void {

    // Keep track if we need to invalidate the cache of referenced assets so
    // the computed 'group' field is updated.
    $update_asset_cache = FALSE;

    // If the log is an active group assignment, invalidate the cache.
    if ($this->isActiveGroupAssignment($log)) {
      $update_asset_cache = TRUE;
    }

    // If updating an existing group assignment log, invalidate the cache.
    // This catches group assignment logs changing from done to pending.
    if (!empty($log->original) && $this->isActiveGroupAssignment($log->original)) {
      $update_asset_cache = TRUE;
    }

    // If an update is not necessary, bail.
    if (!$update_asset_cache) {
      return;
    }

    // Build a list of cache tags.
    // @todo Only invalidate cache if the log changes the asset's current group. This might be different for each asset.
    $tags = [];

    // Include assets that were previously referenced.
    if (!empty($log->original)) {
      foreach ($log->original->get('asset')->referencedEntities() as $asset) {
        array_push($tags, ...$asset->getCacheTags());
      }
    }

    // Include assets currently referenced by the log.
    foreach ($log->get('asset')->referencedEntities() as $asset) {
      array_push($tags, ...$asset->getCacheTags());
    }

    // Invalidate the cache tags.
    $this->cacheTagsInvalidator->invalidateTags($tags);
  }

  /**
   * Invalidate group member cache when a group's location changes.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  protected function invalidateGroupMemberAssetCacheOnMovement(LogInterface $log) {
    // Keep track if we need to invalidate the cache of referenced assets so
    // the computed 'location' and 'geometry' fields are updated.
    $update_asset_cache = FALSE;

    // If the log is a 'done' movement log, invalidate the cache.
    if (LocationLogEventSubscriber::isActiveMovementLog($log)) {
      $update_asset_cache = TRUE;
    }

    // If updating an existing 'done' movement log, invalidate the cache.
    // This catches any movement logs changing from done to pending.
    if (!empty($log->original) && LocationLogEventSubscriber::isActiveMovementLog($log->original)) {
      $update_asset_cache = TRUE;
    }

    // If an update is not necessary, bail.
    if (!$update_asset_cache) {
      return;
    }

    // Build a list of cache tags.
    // @todo Only invalidate cache if the movement log changes the group's current location. This might be different for each asset.
    $tags = [];

    // Include group assets that were previously referenced.
    if (!empty($log->original)) {

      // Get all group assets.
      $group_assets = array_filter($log->original->get('asset')->referencedEntities(), function (AssetInterface $asset) {
        return $asset->bundle() === 'group';
      });

      // Collect group member cache tags.
      $member_tags = array_map(function (AssetInterface $asset) {
        return $asset->getCacheTags();
      }, $this->groupMembership->getGroupMembers($group_assets));
      array_push($tags, ...array_merge(...$member_tags));
    }

    // Include group assets currently referenced by the log.
    $group_assets = array_filter($log->get('asset')->referencedEntities(), function (AssetInterface $asset) {
      return $asset->bundle() === 'group';
    });

    // Collect group member cache tags.
    $member_tags = array_map(function (AssetInterface $asset) {
      return $asset->getCacheTags();
    }, $this->groupMembership->getGroupMembers($group_assets));
    array_push($tags, ...array_merge(...$member_tags));

    // Invalidate the cache tags.
    $this->cacheTagsInvalidator->invalidateTags($tags);
  }

  /**
   * Helper funtion to determine if a log is an active group assignment.
   *
   * Logs are an active group assignment if status = done,
   * is_group_assignment = true, and the timestamp is not in the future.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The log to check.
   *
   * @return bool
   *   Boolean indicating if the log is an active group assignment.
   */
  protected function isActiveGroupAssignment(LogInterface $log): bool {
    return $log->get('status')->value == 'done' && $log->get('is_group_assignment')->value && $log->get('timestamp')->value <= $this->time->getCurrentTime();
  }

}
