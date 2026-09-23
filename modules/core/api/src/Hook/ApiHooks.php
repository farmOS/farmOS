<?php

declare(strict_types=1);

namespace Drupal\farm_api\Hook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\jsonapi\JsonApiFilter;

/**
 * Api hook implementations for farm_api.
 */
class ApiHooks {

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

    // Only allow JSON:API filtering for assets and logs.
    if (!in_array($entity_type->id(), [
      'asset',
      'log',
    ])) {
      return [];
    }

    // Only allow authenticated users to filter.
    if ($account->isAnonymous()) {
      return [];
    }

    // Allow filtering.
    return [JsonApiFilter::AMONG_ALL => AccessResult::allowed()];
  }

}
