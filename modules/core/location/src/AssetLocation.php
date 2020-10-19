<?php

namespace Drupal\farm_location;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Asset location logic.
 */
class AssetLocation implements AssetLocationInterface {

  /**
   * Log location service.
   *
   * @var \Drupal\farm_location\LogLocationInterface
   */
  protected LogLocationInterface $logLocation;

  /**
   * Log query factory.
   *
   * @var \Drupal\farm_log\LogQueryFactoryInterface
   */
  protected LogQueryFactoryInterface $logQueryFactory;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Class constructor.
   *
   * @param \Drupal\farm_location\LogLocationInterface $log_location
   *   Log location service.
   * @param \Drupal\farm_log\LogQueryFactoryInterface $log_query_factory
   *   Log query factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(LogLocationInterface $log_location, LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time) {
    $this->logLocation = $log_location;
    $this->logQueryFactory = $log_query_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('log.location'),
      $container->get('farm.log_query'),
      $container->get('entity_type.manager'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function hasLocation(AssetInterface $asset): bool {
    $log = $this->getMovementLog($asset);
    if (empty($log)) {
      return FALSE;
    }
    return $this->logLocation->hasLocation($log);
  }

  /**
   * {@inheritdoc}
   */
  public function hasGeometry(AssetInterface $asset): bool {
    $log = $this->getMovementLog($asset);
    if (empty($log)) {
      return FALSE;
    }
    return $this->logLocation->hasGeometry($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation(AssetInterface $asset): array {
    $log = $this->getMovementLog($asset);
    if (empty($log)) {
      return [];
    }
    return $this->logLocation->getLocation($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getGeometry(AssetInterface $asset): string {
    $log = $this->getMovementLog($asset);
    if (empty($log)) {
      return '';
    }
    return $this->logLocation->getGeometry($log);
  }

  /**
   * {@inheritdoc}
   */
  public function getMovementLog(AssetInterface $asset): ?LogInterface {

    // Query for movement logs that reference the asset.
    $options = [
      'asset' => $asset,
      'timestamp' => $this->time->getRequestTime(),
      'status' => 'done',
      'limit' => 1,
    ];
    $query = $this->logQueryFactory->getQuery($options);
    $query->condition('movement', TRUE);
    $log_ids = $query->execute();

    // Bail if no logs are found.
    if (empty($log_ids)) {
      return NULL;
    }

    // Load the first log.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = $this->entityTypeManager->getStorage('log')->load(reset($log_ids));

    // Return the log, if available.
    if (!empty($log)) {
      return $log;
    }

    // Otherwise, return NULL.
    return NULL;
  }

}
