<?php

namespace Drupal\data_stream\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\data_stream\Entity\DataStreamInterface;

/**
 * Class for data stream events.
 */
class DataStreamEvent extends Event {

  const DATA_RECEIVE = 'data_stream_data_receive';

  /**
   * The data steam entity.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  public DataStreamInterface $dataStream;

  /**
   * The context associated with the event.
   *
   * @var array
   */
  public array $context;

  /**
   * Constructs the DataStreamEvent object.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $data_stream
   *   The data stream entity.
   * @param array $context
   *   The event context.
   */
  public function __construct(DataStreamInterface $data_stream, array $context = []) {
    $this->dataStream = $data_stream;
    $this->context = $context + ['data_stream' => $data_stream];
  }

}
