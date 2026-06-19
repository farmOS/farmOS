<?php

/**
 * @file
 * Post update hooks for the plan module.
 */

declare(strict_types=1);

use Drupal\Core\Entity\Form\RevisionRevertForm;
use Drupal\Core\Entity\Routing\RevisionHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\QueryAccess\UncacheableQueryAccessHandler;
use Drupal\system\Entity\Action;
use Drupal\views\Entity\View;

/**
 * Move archived plan status to boolean field.
 */
function plan_post_update_move_archived_status(&$sandbox) {

  // Get the Drupal entity definition update manager.
  $update_manager = \Drupal::entityDefinitionUpdateManager();

  // Delete the old archived field.
  $storage_definition = $update_manager->getFieldStorageDefinition('archived', 'plan');
  $update_manager->uninstallFieldStorageDefinition($storage_definition);

  // Install the new boolean archived field.
  $field_definition = BaseFieldDefinition::create('boolean')
    ->setLabel(t('Archived'))
    ->setDescription(t('Whether the plan is archived.'))
    ->setRevisionable(TRUE)
    ->setSetting('on_label', 'Yes')
    ->setSetting('off_label', 'No')
    ->setDisplayOptions('view', [
      'label' => 'inline',
      'type' => 'boolean',
      'settings' => [
        'format' => 'default',
        'format_custom_false' => '',
        'format_custom_true' => '',
      ],
      'weight' => 100,
    ])
    ->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'settings' => [
        'display_label' => TRUE,
      ],
      'weight' => 100,
    ]);
  $update_manager->installFieldStorageDefinition('archived', 'plan', 'plan', $field_definition);

  // Archive plans with a status of archived.
  \Drupal::database()->query("UPDATE {plan_field_data} SET archived = 0 WHERE status != 'archived'");
  \Drupal::database()->query("UPDATE {plan_field_data} SET archived = 1 WHERE status = 'archived'");
  \Drupal::database()->query("UPDATE {plan_field_revision} SET archived = 0 WHERE status != 'archived'");
  \Drupal::database()->query("UPDATE {plan_field_revision} SET archived = 1 WHERE status = 'archived'");

  // Change the status of archived plans to active.
  \Drupal::database()->query("UPDATE {plan_field_data} SET status = 'active' WHERE status = 'archived'");
  \Drupal::database()->query("UPDATE {plan_field_revision} SET status = 'active' WHERE status = 'archived'");

  // Rename plan_activate_action action configuration entity to
  // plan_unarchive_action.
  $action = Action::load('plan_activate_action');
  if (!empty($action)) {
    $action->delete();
    $action->setPlugin('plan_unarchive_action');
    $action->set('id', 'plan_unarchive_action');
    $action->save();
  }
}

/**
 * Remove the plan_admin View.
 */
function plan_post_update_remove_admin_view(&$sandbox) {
  $view = View::load('plan_admin');
  $view->delete();
}

/**
 * Add revision_data_table to plan entity type definition.
 */
function plan_post_update_revision_data_table(&$sandbox) {
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('plan');
  $entity_type->set('revision_data_table', 'plan_field_revision');
  $manager->updateEntityType($entity_type);
}

/**
 * Update plan entity type definition.
 */
function plan_post_update_query_entity_type_definition(&$sandbox) {

  // This applies updates to the plan entity type definition that is stored in
  // the key_value database table. These should have been applied as individual
  // update hooks when the changes were made, but they were not, so this ensures
  // the updates are applied. Specific commit hashes are referenced for each
  // change below.
  // @see https://github.com/farmOS/farmOS/issues/1090
  /** @var \Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface $manager */
  $manager = \Drupal::service('entity.definition_update_manager');
  $entity_type = $manager->getEntityType('plan');

  // Remove collection link.
  // @see https://github.com/farmOS/farmOS/commit/d23951ac3e4658d5c6bcefc2f09cb9d6519ddcc3
  $links = $entity_type->getLinkTemplates();
  if (isset($links['collection'])) {
    unset($links['collection']);
    $entity_type->set('links', $links);
  }

  // Set the query_access handler class.
  // @see https://github.com/farmOS/farmOS/commit/cbd33c3bd6e10327a48a0871b93991799a3f1a82
  $entity_type->setHandlerClass('query_access', UncacheableQueryAccessHandler::class);

  // Set the entity collection_permission.
  // @see https://github.com/farmOS/farmOS/commit/c41ac8ea4bd801b9330ff6423220e6a8b0471c19
  $entity_type->set('collection_permission', 'access plan collection');

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

/**
 * Implements hook_removed_post_updates().
 */
function plan_removed_post_updates() {
  return [
    'plan_post_update_install_plan_record' => '4.x',
    'plan_post_update_remove_plan_record_data_table' => '4.x',
  ];
}
