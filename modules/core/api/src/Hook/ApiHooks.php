<?php

declare(strict_types=1);

namespace Drupal\farm_api\Hook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\jsonapi\JsonApiFilter;

/**
 * Api hook implementations for farm_api.
 */
class ApiHooks {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Implements hook_farm_api_allow_resource_types().
   */
  #[Hook('farm_api_allow_resource_types')]
  public function farmApiAllowResourceTypes() {

    // Allow farmOS core entity types on behalf of the modules that provide
    // them.
    return [
      'asset',
      'asset_type',
      'data_stream',
      'data_stream_type',
      'file',
      'log',
      'log_type',
      'organization',
      'organization_type',
      'plan',
      'plan_type',
      'plan_record',
      'plan_record_type',
      'quantity',
      'quantity_type',
      'taxonomy_term',
      'taxonomy_vocabulary',
      'user',
      'user_role',
    ];
  }

  /**
   * Implements hook_jsonapi_entity_filter_access().
   */
  #[Hook('jsonapi_entity_filter_access')]
  public function jsonapiEntityFilterAccess(EntityTypeInterface $entity_type, AccountInterface $account) {

    // Only allow JSON:API filtering for core farmOS entities.
    if (!in_array($entity_type->id(), [
      'asset',
      'data_stream',
      'log',
      'organization',
      'plan',
      'quantity',
    ])) {
      return [];
    }

    // Only allow authenticated users to filter.
    if ($account->isAnonymous()) {
      return [];
    }

    // Skip entities that do not use the Entity API query access handler.
    if (!$entity_type->hasHandlerClass('query_access')) {
      return [];
    }

    // Load the query access handler and check view access to build a set of
    // query conditions. If they are always false, then skip this entity type.
    /** @var \Drupal\entity\QueryAccess\QueryAccessHandlerInterface $query_access */
    $query_access = $this->entityTypeManager->getHandler($entity_type->id(), 'query_access');
    $conditions = $query_access->getConditions('view', $account);
    if ($conditions->isAlwaysFalse()) {
      return [];
    }

    // Allow filtering.
    return [JsonApiFilter::AMONG_ALL => AccessResult::allowed()->addCacheableDependency($conditions)];
  }

}
