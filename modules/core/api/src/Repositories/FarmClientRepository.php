<?php

namespace Drupal\farm_api\Repositories;

use Drupal\simple_oauth\Entities\ClientEntity;
use Drupal\simple_oauth\Repositories\ClientRepository;

/**
 * Decorates the simple_oauth ClientRepository.
 *
 * Load OAuth clients by the consumer.client_id field rather than UUID.
 */
class FarmClientRepository extends ClientRepository {

  /**
   * {@inheritdoc}
   */
  public function getClientEntity($client_identifier) {
    $client_drupal_entity = parent::getClientEntity($client_identifier);

    if (!empty($client_drupal_entity)) {
      return $client_drupal_entity;
    }

    $client_drupal_entities = $this->entityTypeManager
      ->getStorage('consumer')
      ->loadByProperties(['client_id' => $client_identifier]);

    // Check if the client is registered.
    if (empty($client_drupal_entities)) {
      return NULL;
    }
    /** @var \Drupal\consumers\Entity\Consumer $client_drupal_entity */
    $client_drupal_entity = reset($client_drupal_entities);

    return new ClientEntity($client_drupal_entity);
  }

}
