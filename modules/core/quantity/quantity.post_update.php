<?php

/**
 * @file
 * Post update hooks for the quantity module.
 */

declare(strict_types=1);

use Drupal\entity\QueryAccess\UncacheableQueryAccessHandler;

/**
 * Update quantity entity type definition.
 */
function quantity_post_update_entity_type_definition(&$sandbox) {

  // This applies updates to the quantity entity type definition that is stored
  // in the key_value database table. These should have been applied as
  // individual update hooks when the changes were made, but they were not, so
  // this ensures the updates are applied. Specific commit hashes are referenced
  // for each change below.
  // @see https://github.com/farmOS/farmOS/issues/1090
  /** @var \Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface $manager */
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('quantity');

  // Use bundle permission granularity.
  // @see https://github.com/farmOS/farmOS/commit/9b9871c3bab586e199b1213b699584c49983c7cf
  $entity_type->set('permission_granularity', 'bundle');

  // Set delete-multiple-form link.
  // @see https://github.com/farmOS/farmOS/commit/873f91cda5de3406598d0c7f37761e96bcad60aa
  $entity_type->setLinkTemplate('delete-multiple-form', '/quantity/delete');

  // Set the query_access handler class.
  // @see https://github.com/farmOS/farmOS/commit/cbd33c3bd6e10327a48a0871b93991799a3f1a82
  $entity_type->setHandlerClass('query_access', UncacheableQueryAccessHandler::class);

  // Set the entity collection_permission.
  // @see https://github.com/farmOS/farmOS/commit/c41ac8ea4bd801b9330ff6423220e6a8b0471c19
  $entity_type->set('collection_permission', 'access quantity collection');

  // Update the entity type definition.
  $manager->updateEntityType($entity_type);
}

/**
 * Implements hook_removed_post_updates().
 */
function quantity_removed_post_updates() {
  return [
    'quantity_post_update_plain_text_view_mode' => '4.x',
    'quantity_post_update_delete_action' => '4.x',
  ];
}
