<?php

declare(strict_types=1);

namespace Drupal\farm_entity\Hook;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableEntityBundleInterface;
use Drupal\Core\Entity\TranslatableRevisionableInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Hook\Order\OrderBefore;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity\EntityAccessControlHandler;
use Drupal\entity\EntityPermissionProvider;
use Drupal\farm_entity\BundlePlugin\FarmEntityBundlePluginHandler;
use Drupal\farm_entity\Routing\BundleEntityTypeRouteProvider;
use Drupal\farm_entity\Routing\EntityRouteProvider;

/**
 * Entity hook implementations for farm_entity.
 */
class EntityHooks {

  public function __construct(
    protected AccountInterface $currentUser,
    protected TimeInterface $time,
  ) {}

  /**
   * Implements hook_entity_type_build().
   *
   * Make sure this module's implementation runs before that of the entity
   * module, so that we can override the bundle plugin handler, and so that we
   * can set the Log entity type's bundle_plugin_type.
   */
  #[Hook('entity_type_build', order: new OrderBefore(['entity']))]
  public function entityTypeBuild(array &$entity_types) {

    // Allow the "view label" operation on the bundle entity type.
    foreach (['asset', 'log', 'organization', 'plan', 'quantity', 'data_stream'] as $entity_type) {
      if (!empty($entity_types[$entity_type])) {
        $bundle_entity_type = $entity_types[$entity_type]->getBundleEntityType();
        $entity_types[$bundle_entity_type]->setHandlerClass('access', EntityAccessControlHandler::class);
        $entity_types[$bundle_entity_type]->setHandlerClass('permission_provider', EntityPermissionProvider::class);
      }
    }

    // Enable the use of bundle plugins on specific entity types.
    foreach (['asset', 'log', 'organization', 'plan', 'plan_record', 'quantity'] as $entity_type) {
      if (!empty($entity_types[$entity_type])) {
        $entity_types[$entity_type]->set('bundle_plugin_type', $entity_type . '_type');
        $entity_types[$entity_type]->setHandlerClass('bundle_plugin', FarmEntityBundlePluginHandler::class);

        // Override default entity route provider class.
        $route_providers = $entity_types[$entity_type]->getRouteProviderClasses();
        $route_providers['default'] = EntityRouteProvider::class;
        $entity_types[$entity_type]->setHandlerClass('route_provider', $route_providers);

        // Deny access to the entity type add form. New entity types of entities
        // with bundle plugins cannot be created in the UI.
        // See https://www.drupal.org/project/farm/issues/3196423
        $bundle_entity_type = $entity_types[$entity_type]->getBundleEntityType();
        $route_providers = $entity_types[$bundle_entity_type]->getRouteProviderClasses();
        $route_providers['default'] = BundleEntityTypeRouteProvider::class;
        $entity_types[$bundle_entity_type]->setHandlerClass('route_provider', $route_providers);
      }
    }
  }

  /**
   * Implements hook_entity_type_alter().
   */
  #[Hook('entity_type_alter')]
  public function entityTypeAlter(array &$entity_types) {
    /** @var \Drupal\Core\Entity\EntityTypeInterface[] $entity_types */

    // Remove the form for reverting entity revisions.
    // We do this because farmOS modules provide entity constraint validation
    // logic that may depend on other entities in the database (not just the
    // data in the entity being reverted), and Drupal does not validate entities
    // when they are reverted. This could result in entities that no longer
    // validate, leaving the database in a state that would normally be
    // considered invalid.
    foreach ($entity_types as $entity_type) {
      if ($entity_type->hasLinkTemplate('revision-revert-form')) {
        $links = $entity_type->getLinkTemplates();
        unset($links['revision-revert-form']);
        $entity_type->set('links', $links);
      }
    }
  }

  /**
   * Implements hook_entity_presave().
   *
   * Forces revisions on all farm entities if the entity type supports them and
   * the bundle has them enabled. This removes the option for users to disable a
   * revision per-entity but as JSON:API doesn't support revisions yet, this is
   * a trade-off that allows us to create revisions consistently on both the UI
   * and the API.
   */
  #[Hook('entity_presave')]
  public function entityPresave(EntityInterface $entity) {

    // Only apply to farm controlled entities.
    $entity_types = [
      'asset',
      'log',
      'organization',
      'plan',
      'quantity',
    ];
    if (!in_array($entity->getEntityTypeId(), $entity_types) || !$entity instanceof RevisionLogInterface || !$entity instanceof FieldableEntityInterface) {
      return;
    }

    // Always create new revisions when an entity is saved.
    // This ensures a proper audit trail is available for important records.
    $entity_type = $entity->get($entity->getEntityType()->getKey('bundle'))->entity;
    if ($entity_type instanceof RevisionableEntityBundleInterface && $entity_type->shouldCreateNewRevision() && $entity->getEntityType()->isRevisionable()) {

      // If this is a log, and it does not have a name, do not create a new
      // revision so that the log module's LogStorage::doPostSave() logic can
      // fill in its name and save it again. We don't want to create two
      // revisions during this process.
      // @todo this doesn't work as expected when saving an existing log and clearing its name
      if ($entity->getEntityTypeId() == 'log' && (is_null($entity->label()) || (!empty($entity->getOriginal()) && is_null($entity->getOriginal()->label())))) {
        $entity->setNewRevision(FALSE);
        return;
      }

      // Always create a new revision.
      $entity->setNewRevision(TRUE);

      // Always consider revision translations affected. Without this, if an
      // entity is saved with a revision log message, but without any changes to
      // the entity itself, then Drupal core will hide this revision from the
      // list of revisions in the UI.
      // @see \Drupal\Core\Entity\ContentEntityStorageBase::populateAffectedRevisionTranslations()
      // @see \Drupal\Core\Entity\ContentEntityBase::hasTranslationChanges()
      // @see https://www.drupal.org/project/drupal/issues/2722307
      // @see https://www.drupal.org/project/drupal/issues/2933518
      if ($entity instanceof TranslatableRevisionableInterface) {
        $entity->setRevisionTranslationAffected(TRUE);
      }

      // Set the user ID and creation time.
      $entity->setRevisionUserId($this->currentUser->id());
      $entity->setRevisionCreationTime($this->time->getRequestTime());
    }
  }

}
