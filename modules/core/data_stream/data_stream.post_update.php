<?php

/**
 * @file
 * Post update functions for data_stream module.
 */

declare(strict_types=1);

use Drupal\entity\QueryAccess\UncacheableQueryAccessHandler;

/**
 * Update data_stream entity type definition.
 */
function data_stream_post_update_entity_type_definition(&$sandbox) {

  // This applies updates to the data_stream entity type definition that is
  // stored in the key_value database table. These should have been applied as
  // individual update hooks when the changes were made, but they were not, so
  // this ensures the updates are applied. Specific commit hashes are referenced
  // for each change below.
  // @see https://github.com/farmOS/farmOS/issues/1090
  /** @var \Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface $manager */
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('data_stream');

  // Remove collection link.
  // @see https://github.com/farmOS/farmOS/commit/ebde335b00618ec6aca816256f9cfebb3fb71a41
  $links = $entity_type->getLinkTemplates();
  if (isset($links['collection'])) {
    unset($links['collection']);
    $entity_type->set('links', $links);
  }

  // Set the query_access handler class.
  // @see https://github.com/farmOS/farmOS/commit/cbd33c3bd6e10327a48a0871b93991799a3f1a82
  $entity_type->setHandlerClass('query_access', UncacheableQueryAccessHandler::class);

  // Update the entity type definition.
  $manager->updateEntityType($entity_type);
}
