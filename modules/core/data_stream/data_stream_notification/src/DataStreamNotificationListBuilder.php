<?php

namespace Drupal\data_stream_notification;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a data stream notification list builder.
 */
class DataStreamNotificationListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $notification) {
    $row = parent::buildRow($notification);
    return [
      'data' => [
        'label' => [
          'data' => [
            '#plain_text' => $notification->label(),
          ],
        ],
        'machine_name' => [
          'data' => [
            '#plain_text' => $notification->id(),
          ],
        ],
        'operations' => $row['operations'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    return [
      'label' => [
        'data' => $this->t('Label'),
      ],
      'machine_name' => [
        'data' => $this->t('Machine name'),
      ],
      'operations' => [
        'data' => $this->t('Operations'),
      ],
    ];
  }

}
