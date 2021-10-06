<?php

namespace Drupal\Tests\farm_test\Kernel;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityInterface;

/**
 * Trait for testing invalidation of entity cache tags.
 *
 * During a test use populateEntityTestCache to set a cache value dependent on
 * an entity's cache tags. Later in the test use assertEntityTestCache to
 * ensure the cache value's existence after cache tags are invalidated.
 */
trait FarmEntityCacheTestTrait {

  /**
   * Populate a cache value that is dependent on an entity's cache tags.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to cache data for.
   * @param string $data
   *   The data to cache. Defaults to a simple string.
   */
  protected function populateEntityTestCache(EntityInterface $entity, string $data = 'data') {
    \Drupal::cache()->set($this->getEntityCacheId($entity), $data, Cache::PERMANENT, $entity->getCacheTags());
  }

  /**
   * Assert that a cache value dependent on an entity's cache tags exists.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity cache to check.
   * @param bool $cache_hit
   *   Boolean indicating if the cache lookup should result in a cache hit.
   */
  protected function assertEntityTestCache(EntityInterface $entity, bool $cache_hit) {
    $cache_result = \Drupal::cache()->get($this->getEntityCacheId($entity));
    $this->assertEquals($cache_hit, (bool) $cache_result);
  }

  /**
   * Helper method to build the entity test cache ID.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to cache data for.
   *
   * @return string
   *   The entity's test cache ID.
   */
  protected function getEntityCacheId(EntityInterface $entity): string {
    $entity_type = $entity->getEntityTypeId();
    $entity_id = $entity->id();
    return "farm_entity_cache_test:$entity_type:$entity_id";
  }

}
