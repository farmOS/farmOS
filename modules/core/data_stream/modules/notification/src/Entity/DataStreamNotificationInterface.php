<?php

namespace Drupal\data_stream_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * Provides an interface for defining data stream entities.
 */
interface DataStreamNotificationInterface extends ConfigEntityInterface, EntityWithPluginCollectionInterface {

  /**
   * Helper function to get the notification state.
   *
   * @return array
   *   The notification state.
   */
  public function getState(): array;

  /**
   * Helper function to get the notification active state.
   *
   * @return bool
   *   A boolean indicating the notification active state.
   */
  public function isActive(): bool;

  /**
   * Helper function to reset the notification state.
   *
   * @param bool $active
   *   Boolean indicating if the notification is active.
   *
   * @return array
   *   The new notification state.
   */
  public function resetState(bool $active): array;

  /**
   * Helper function to increment the notification state.
   *
   * @param string $key
   *   The state key to set. Either activate_count or deactivate_count.
   *
   * @return array
   *   The new notification state.
   */
  public function incrementState(string $key): array;

}
