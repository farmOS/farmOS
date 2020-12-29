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
   * The name of the asset geometry field.
   *
   * @var string
   */
  const ASSET_FIELD_GEOMETRY = 'geometry';

  /**
   * The name of the asset boolean fixed field.
   *
   * @var string
   */
  const ASSET_FIELD_FIXED = 'fixed';

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
  public function isFixed(AssetInterface $asset): bool {
    return !empty($asset->{static::ASSET_FIELD_FIXED}->value);
  }

  /**
   * {@inheritdoc}
   */
  public function hasLocation(AssetInterface $asset): bool {
    if ($this->isFixed($asset)) {
      return FALSE;
    }
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
    if ($this->isFixed($asset)) {
      return !$asset->get(static::ASSET_FIELD_GEOMETRY)->isEmpty();
    }
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
    if ($this->isFixed($asset)) {
      return [];
    }
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
    if ($this->isFixed($asset)) {
      return $asset->get(static::ASSET_FIELD_GEOMETRY)->value ?? '';
    }
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
