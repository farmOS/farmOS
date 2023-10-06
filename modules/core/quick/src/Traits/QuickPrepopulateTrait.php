<?php

namespace Drupal\farm_quick\Traits;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides methods for loading prepopulated entity references.
 */
trait QuickPrepopulateTrait {

  /**
   * Returns the quick form ID.
   *
   * This must be implemented by the quick form class that uses this trait.
   *
   * @see \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface
   *
   * @return string
   *   The quick form ID.
   */
  abstract public function getQuickId();

  /**
   * Get prepopulated entities.
   *
   * @param string $entity_type
   *   The entity type to prepopulate.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   An array of entities.
   */
  protected function getPrepopulatedEntities(string $entity_type, FormStateInterface $form_state) {

    // Initialize a temporary value in the form state.
    if (!$form_state->hasTemporaryValue("quick_prepopulate_$entity_type")) {
      $this->initPrepoluatedEntities($entity_type, $form_state);
    }

    // Return the prepopulated entities saved in the form state.
    $entity_ids = $form_state->getTemporaryValue("quick_prepopulate_$entity_type") ?? [];
    return \Drupal::entityTypeManager()->getStorage($entity_type)->loadMultiple($entity_ids);
  }

  /**
   * Helper function to initialize prepopulated entities in the form state.
   *
   * @param string $entity_type
   *   The entity type to prepopulate.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  protected function initPrepoluatedEntities(string $entity_type, FormStateInterface $form_state) {

    // Save the current user.
    $user = \Drupal::currentUser();

    // Load the temp store for the quick form.
    /** @var \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory */
    $temp_store_factory = \Drupal::service('tempstore.private');
    $temp_store = $temp_store_factory->get('farm_quick.' . $this->getQuickId());

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

    // Only include the unique ids.
    $entity_ids = array_unique(array_merge($temp_store_entity_ids, $query_entity_ids));

    // Filter to entities the user has access to.
    $accessible_entities = [];
    if (!empty($entity_ids)) {
      // Return entities the user has access to.
      $entities = \Drupal::entityTypeManager()->getStorage($entity_type)->loadMultiple($entity_ids);
      $accessible_entities = array_filter($entities, function (EntityInterface $asset) use ($user) {
        return $asset->access('view', $user);
      });
    }

    // Save the accessible entity ids as a temporary value in the form state.
    $accessible_entity_ids = array_map(function (EntityInterface $entity) {
      return $entity->id();
    }, $accessible_entities);
    $form_state->setTemporaryValue("quick_prepopulate_$entity_type", $accessible_entity_ids);

    // Finally, remove the entities from the temp store.
    $temp_store->delete($temp_store_key);
  }

}
