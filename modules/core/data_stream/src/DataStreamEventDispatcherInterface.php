<?php

namespace Drupal\data_stream;

/**
 * The DataStreamEventDispatcherInterface.
 *
 * An interface for data stream types to declare support for data stream events.
 */
interface DataStreamEventDispatcherInterface {

  /**
   * Get the events supported by this data stream type.
   *
   * Returns an array of event contexts keyed by event ID.
   *
   * @return array
   *   The array of event contexts.
   */
  public function getSupportedEvents(): array;

}
