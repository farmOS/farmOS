<?php

namespace Drupal\farm_log;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Factory for generating a log query.
 */
class LogQueryFactory implements LogQueryFactoryInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuery(): QueryInterface {

    // Start with a standard log entity query.
    $query = $this->entityTypeManager->getStorage('log')->getQuery();

    // Add a tag.
    $query->addTag('farm.log_query');

    // Sort by timestamp and then log ID, descending.
    $query->sort('timestamp', 'DESC');
    $query->sort('id', 'DESC');

    // Return the query.
    return $query;
  }

}
