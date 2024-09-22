<?php

namespace Drupal\farm_timeline\Controller;

use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\farm_log\AssetLogsInterface;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base controller for timeline data endpoints.
 */
abstract class TimelineControllerBase extends ControllerBase {

  /**
   * The asset logs service.
   *
   * @var \Drupal\farm_log\AssetLogsInterface
   */
  protected $assetLogs;

  /**
   * The UUID service.
   *
   * @var \Drupal\Component\Uuid\UuidInterface
   */
  protected $uuidService;

  /**
   * The typed data manager interface.
   *
   * @var \Drupal\Core\TypedData\TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * GrazingPlanTimeline constructor.
   *
   * @param \Drupal\farm_log\AssetLogsInterface $asset_logs
   *   The asset logs service.
   * @param \Drupal\Component\Uuid\UuidInterface $uuid_service
   *   The UUID service.
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typed_data_manager
   *   The typed data manager interface.
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   The serializer service.
   */
  public function __construct(AssetLogsInterface $asset_logs, UuidInterface $uuid_service, TypedDataManagerInterface $typed_data_manager, SerializerInterface $serializer) {
    $this->assetLogs = $asset_logs;
    $this->uuidService = $uuid_service;
    $this->typedDataManager = $typed_data_manager;
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('asset.logs'),
      $container->get('uuid'),
      $container->get('typed_data_manager'),
      $container->get('serializer'),
    );
  }

  /**
   * Helper function for building a single log task.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The log entity.
   *
   * @return array
   *   Returns an array representing a single log task.
   */
  protected function buildLogTask(LogInterface $log) {
    return [
      'id' => $this->uuidService->generate(),
      'link_url' => $log->toUrl()->toString(),
      'start' => $log->get('timestamp')->value,
      'end' => $log->get('timestamp')->value + 86400,
      'meta' => [
        'label' => $log->label(),
        'entity_id' => $log->id(),
        'entity_type' => 'log',
        'entity_bundle' => $log->bundle(),
        'log_status' => $log->get('status')->value,
      ],
      'classes' => [
        'log',
        'log--' . $log->bundle(),
        'log--status-' . $log->get('status')->value,
      ],
    ];
  }

}
