<?php

/**
 * @file
 * Post update functions for organization module.
 */

declare(strict_types=1);

use Drupal\Core\Entity\Form\RevisionRevertForm;
use Drupal\Core\Entity\Routing\RevisionHtmlRouteProvider;

/**
 * Add revision_data_table to organization entity type definition.
 */
function organization_post_update_revision_data_table(&$sandbox) {
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('organization');
  $entity_type->set('revision_data_table', 'organization_field_revision');
  $manager->updateEntityType($entity_type);
}

/**
 * Update organization entity type definition.
 */
function organization_post_update_entity_type_definition(&$sandbox) {

  // This applies updates to the organization entity type definition that is
  // stored in the key_value database table. These should have been applied as
  // individual update hooks when the changes were made, but they were not, so
  // this ensures the updates are applied. Specific commit hashes are referenced
  // for each change below.
  // @see https://github.com/farmOS/farmOS/issues/1090
  /** @var \Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface $manager */
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('organization');

  // Use Drupal core's RevisionHtmlRouteProvider, and define the revision-revert
  // form class.
  // @see https://github.com/farmOS/farmOS/commit/25c3a66514cdc357a575a13bafbf6349c994be53
  $route_providers = $entity_type->getRouteProviderClasses();
  $route_providers['revision'] = RevisionHtmlRouteProvider::class;
  $entity_type->setHandlerClass('route_provider', $route_providers);
  $entity_type->setFormClass('revision-revert', RevisionRevertForm::class);

  // Update the entity type definition.
  $manager->updateEntityType($entity_type);
}
