<?php

namespace Drupal\data_stream\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Interface for the DataStream entity type.
 */
interface DataStreamInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Returns an instance of the data stream plugin.
   *
   * @return \Drupal\data_stream\DataStreamPluginInterface
   *   The data stream plugin.
   */
  public function getPlugin();

  /**
   * Gets the data stream name.
   *
   * @return string
   *   The data stream name.
   */
  public function getName();

  /**
   * Sets the data stream name.
   *
   * @param string $name
   *   The data stream name.
   *
   * @return \Drupal\data_stream\Entity\DataStreamInterface
   *   The data stream entity.
   */
  public function setName(string $name);

  /**
   * Gets the data stream creation timestamp.
   *
   * @return int
   *   Creation timestamp of the data stream.
   */
  public function getCreatedTime();

  /**
   * Sets the data_stream creation timestamp.
   *
   * @param int $timestamp
   *   Creation timestamp of the data stream.
   *
   * @return \Drupal\data_stream\Entity\DataStreamInterface
   *   The data_stream entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the data stream private key.
   *
   * @return string
   *   The data stream private key.
   */
  public function getPrivateKey();

  /**
   * Returns the data stream public property.
   *
   * @return bool
   *   If the data stream is public.
   */
  public function isPublic();

}
