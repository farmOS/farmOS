<?php

namespace Drupal\data_stream;

use Drupal\data_stream\Entity\DataStreamInterface;

/**
 * The DataStreamStorageInterface.
 *
 * A common interface for acting with the DataStream storage.
 */
interface DataStreamStorageInterface {

  /**
   * Get data from the DataStream storage.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The DataStream entity.
   * @param array $params
   *   Parameters.
   *
   * @return array
   *   Array of data.
   */
  public function storageGet(DataStreamInterface $stream, array $params);

  /**
   * Save data to the DataStream storage.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The DataStream entity.
   * @param array $data
   *   Data to save.
   *
   * @return bool
   *   Success.
   */
  public function storageSave(DataStreamInterface $stream, array $data);

}
