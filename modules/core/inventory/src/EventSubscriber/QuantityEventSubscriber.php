<?php

namespace Drupal\farm_inventory\EventSubscriber;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\quantity\Entity\QuantityInterface;
use Drupal\quantity\Event\QuantityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions when quantity entities are saved/deleted.
 */
class QuantityEventSubscriber implements EventSubscriberInterface {

  /**
   * Cache tag invalidator service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected CacheTagsInvalidatorInterface $cacheTagsInvalidator;

  /**
   * QuantityEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   Cache tag invalidator service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cache_tags_invalidator) {
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
      QuantityEvent::PRESAVE => 'quantityPresave',
      QuantityEvent::DELETE => 'quantityDelete',
    ];
  }

  /**
   * Invalidate asset cache when inventory adjustment quantities are updated.
   *
   * @param \Drupal\quantity\Event\QuantityEvent $event
   *   Config crud event.
   */
  public function quantityPresave(QuantityEvent $event) {
    $this->invalidateAssetInventory($event->quantity);
  }

  /**
   * Invalidate asset cache when inventory adjustment quantities are deleted.
   *
   * @param \Drupal\quantity\Event\QuantityEvent $event
   *   Config crud event.
   */
  public function quantityDelete(QuantityEvent $event) {
    $this->invalidateAssetInventory($event->quantity);
  }

  /**
   * Invalidate asset caches when inventory adjustment quantities change.
   *
   * @param \Drupal\quantity\Entity\QuantityInterface $quantity
   *   The Quantity entity.
   */
  protected function invalidateAssetInventory(QuantityInterface $quantity): void {

    // Keep track if we need to invalidate the cache of referenced assets so
    // the computed 'inventory' field is updated.
    $update_asset_cache = FALSE;

    // If the quantity is an inventory adjustment, invalidate the cache.
    if (in_array($quantity->get('inventory_adjustment')->value, ['reset', 'increment', 'decrement'])) {
      $update_asset_cache = TRUE;
    }

    // If an update is not necessary, bail.
    if (!$update_asset_cache) {
      return;
    }

    // Build a list of cache tags.
    // @todo Only invalidate cache if the asset's inventory changes. This might be different for each asset.
    $tags = [];

    // Include the asset referenced by the quantity.
    foreach ($quantity->get('inventory_asset')->referencedEntities() as $asset) {
      array_push($tags, ...$asset->getCacheTags());
    }

    // Invalidate the cache tags.
    $this->cacheTagsInvalidator->invalidateTags($tags);
  }

}
