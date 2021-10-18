<?php

namespace Drupal\farm_inventory\EventSubscriber;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\log\Entity\LogInterface;
use Drupal\log\Event\LogEvent;
use Drupal\quantity\Entity\QuantityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Invalidate asset cache when inventory changes.
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
   * LogEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   Cache tag invalidator service.
   * @param \Drupal\Component\Datetime\TimeInterface $date_time
   *   Datetime time service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cache_tags_invalidator, TimeInterface $date_time) {
    $this->time = $date_time;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
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
    $this->invalidateAssetCacheOnInventoryChange($event->log);
  }

  /**
   * Perform actions on log presave.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function logPresave(LogEvent $event) {
    $this->invalidateAssetCacheOnInventoryChange($event->log);
  }

  /**
   * Invalidate asset caches when assets inventory changes.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  protected function invalidateAssetCacheOnInventoryChange(LogInterface $log): void {

    // Keep track if we need to invalidate the cache of referenced assets so
    // the computed 'inventory' field is updated.
    $update_asset_cache = FALSE;

    // If the log is an active quantity measurement, invalidate the cache.
    if ($this->isActiveQuantityLog($log)) {
      $update_asset_cache = TRUE;
    }

    // If updating an existing inventory log, invalidate the cache.
    // This catches inventory logs changing from done to pending.
    if (!empty($log->original) && $this->isActiveQuantityLog($log->original)) {
      $update_asset_cache = TRUE;
    }

    // If an update is not necessary, bail.
    if (!$update_asset_cache) {
      return;
    }

    // Build a list of cache tags.
    // @todo Only invalidate cache if the log changes the asset's inventory. This might be different for each asset.
    $tags = [];

    // Include assets that were previously referenced by inventory adjustments.
    if (!empty($log->original)) {
      array_push($tags, ...$this->getInventoryAssetCacheTags($log->original));
    }

    // Include assets currently referenced by the log.
    array_push($tags, ...$this->getInventoryAssetCacheTags($log));

    // Invalidate the cache tags.
    $this->cacheTagsInvalidator->invalidateTags($tags);
  }

  /**
   * Helper function to determine if a log is active and has quantities.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The log to check.
   *
   * @return bool
   *   Boolean indicating if the log is active.
   */
  protected function isActiveQuantityLog(LogInterface $log): bool {
    return $log->get('status')->value == 'done' && $log->get('timestamp')->value <= $this->time->getCurrentTime() && !$log->get('quantity')->isEmpty();
  }

  /**
   * Helper function to load asset cache tags from the inventory_asset field.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The log to check.
   *
   * @return string[]
   *   An array of cache tags.
   */
  protected function getInventoryAssetCacheTags(LogInterface $log): array {

    // Filter to only log quantities with an inventory adjustment.
    $quantities = array_filter($log->get('quantity')->referencedEntities(), function (QuantityInterface $quantity) {
      return in_array($quantity->get('inventory_adjustment')->value, ['reset', 'increment', 'decrement']) && !$quantity->get('inventory_asset')->isEmpty();
    });

    // Collect cache tags from assets referenced by the inventory_asset field.
    $cache_tags = array_map(function (QuantityInterface $quantity) {
      $asset_tags = array_map(function (AssetInterface $asset) {
        return $asset->getCacheTags();
      }, $quantity->get('inventory_asset')->referencedEntities());
      return array_merge(...$asset_tags);
    }, $quantities);

    // Return all cache tags.
    return array_merge(...$cache_tags);
  }

}
