<?php

namespace Drupal\farm_group;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Asset group membership logic.
 */
class GroupMembership implements GroupMembershipInterface {

  /**
   * The name of the log group reference field.
   *
   * @var string
   */
  const LOG_FIELD_GROUP = 'group';

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
   * @param \Drupal\farm_log\LogQueryFactoryInterface $log_query_factory
   *   Log query factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time) {
    $this->logQueryFactory = $log_query_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('farm.log_query'),
      $container->get('entity_type.manager'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function hasGroup(AssetInterface $asset): bool {
    $log = $this->getGroupAssignmentLog($asset);
    if (empty($log)) {
      return FALSE;
    }
    return !$log->get(static::LOG_FIELD_GROUP)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup(AssetInterface $asset): array {
    $log = $this->getGroupAssignmentLog($asset);
    if (empty($log)) {
      return [];
    }
    return $log->{static::LOG_FIELD_GROUP}->referencedEntities() ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupAssignmentLog(AssetInterface $asset): ?LogInterface {

    // If the asset is new, no group assignment logs will reference it.
    if ($asset->isNew()) {
      return NULL;
    }

    // Query for group assignment logs that reference the asset.
    $options = [
      'asset' => $asset,
      'timestamp' => $this->time->getRequestTime(),
      'status' => 'done',
      'limit' => 1,
    ];
    $query = $this->logQueryFactory->getQuery($options);
    $query->condition('is_group_assignment', TRUE);
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
