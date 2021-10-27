<?php

namespace Drupal\farm_quick\Traits;

use Drupal\Core\Entity\EntityInterface;

/**
 * Provides methods for loading prepopulated entity references.
 */
trait QuickPrepopulateTrait {

  /**
   * Get the plugin ID.
   *
   * This must be implemented by the quick form class that uses this trait.
   *
   * @return string
   *   The quick form ID.
   */
  abstract protected function getId();

  /**
   * Get prepopulated entities.
   *
   * @param string $entity_type
   *   The entity type to prepopulate.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of entities.
   */
  protected function getPrepopulatedEntities(string $entity_type) {

    // Save the current user.
    $user = \Drupal::currentUser();

    // Load the temp store for the quick form.
    // @todo Clear the temp store after form submission.
    // Can we do this without another helper method on this trait?
    /** @var \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory */
    $temp_store_factory = \Drupal::service('tempstore.private');
    $temp_store = $temp_store_factory->get('farm_quick.' . $this->getId());

    // Load entities from the temp store.
    $temp_store_key = $user->id() . ':' . $entity_type;
    $temp_store_entities = $temp_store->get($temp_store_key) ?? [];

    // Convert entities to entity ids.
    $temp_store_entity_ids = array_map(function (EntityInterface $entity) {
      return $entity->id();
    }, $temp_store_entities);

    // Load entities from the query params.
    $query = \Drupal::request()->query;
    $query_entity_ids = $query->get('asset') ?? [];

    // Wrap in an array, if necessary.
    if (!is_array($query_entity_ids)) {
      $query_entity_ids = [$query_entity_ids];
    }

    // Bail if there are no prepopulated entities.
    $entity_ids = array_unique(array_merge($temp_store_entity_ids, $query_entity_ids));
    if (empty($entity_ids)) {
      return [];
    }

    // Return entities the user has access to.
    $entities = \Drupal::entityTypeManager()->getStorage($entity_type)->loadMultiple($entity_ids);
    return array_filter($entities, function (EntityInterface $asset) use ($user) {
      return $asset->access('view', $user);
    });
  }

}
