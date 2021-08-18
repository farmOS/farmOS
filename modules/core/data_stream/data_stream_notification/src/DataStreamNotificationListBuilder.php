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
  public function load() {
    $entities = [
      'enabled' => [],
      'disabled' => [],
    ];
    foreach (parent::load() as $entity) {
      if ($entity->status()) {
        $entities['enabled'][] = $entity;
      }
      else {
        $entities['disabled'][] = $entity;
      }
    }
    return $entities;
  }

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

  /**
   * {@inheritdoc}
   */
  public function render() {

    $list['#type'] = 'container';

    // Add markup for the enabled table.
    $list['enabled']['heading']['#markup'] = '<h2>' . $this->t('Enabled', [], ['context' => 'Plural']) . '</h2>';
    $list['enabled']['table']['#empty'] = $this->t('There are no enabled notifications.');

    // Add markup for the disabled table.
    $list['disabled']['heading']['#markup'] = '<h2>' . $this->t('Disabled', [], ['context' => 'Plural']) . '</h2>';
    $list['disabled']['table']['#empty'] = $this->t('There are no disabled notifications.');

    // Build separate tables for enabled and disabled.
    $entities = $this->load();
    foreach (['enabled', 'disabled'] as $status) {
      $list[$status]['table'] = [
        '#type' => 'table',
        '#header' => $this->buildHeader(),
      ];

      // Build a row for each entity.
      foreach ($entities[$status] as $entity) {
        if ($row = $this->buildRow($entity)) {
          $list[$status]['table']['#rows'][$entity->id()] = $row;
        }
      }
    }

    return $list;
  }

}
