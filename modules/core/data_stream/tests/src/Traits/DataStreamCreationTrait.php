<?php

namespace Drupal\Tests\data_stream\Traits;

use Drupal\data_stream\DataStreamStorageInterface;
use Drupal\data_stream\Entity\DataStream;
use Drupal\data_stream\Entity\DataStreamInterface;

/**
 * Provides methods to create data stream entities.
 *
 * This trait is meant to be used only by test classes.
 */
trait DataStreamCreationTrait {

  /**
   * Creates a data stream entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\data_stream\Entity\DataStreamInterface
   *   The data stream entity.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createDataStreamEntity(array $values = []) {
    /** @var \Drupal\data_stream\Entity\DataStreamInterface $entity */
    $entity = DataStream::create($values + [
      'name' => $this->randomMachineName(),
    ]);
    $entity->save();
    return $entity;
  }

  /**
   * Helper function to generate data for a basic data stream.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The data stream entity.
   * @param int $count
   *   The number of data points to create.
   * @param string|null $start_time
   *   The start timestamp.
   */
  protected function mockBasicData(DataStreamInterface $stream, $count = 1, $start_time = NULL) {

    $plugin = $stream->getPlugin();

    // Bail if the plugin type doesn't implement a storage interface.
    if (!$plugin instanceof DataStreamStorageInterface) {
      return;
    }

    if (empty($start_time)) {
      $start_time = \Drupal::time()->getCurrentTime();
    }

    $data = [];

    $name = $stream->label();
    $value = 0;
    for ($x = 0; $x < $count; $x++) {
      $data[] = [
        'timestamp' => $start_time,
        $name => $value,
      ];
      $start_time += 86400;
      $value += 1;
    }
    $plugin->storageSave($stream, $data);

  }

}
