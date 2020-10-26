<?php

namespace Drupal\farm_sensor_listener;

use Drupal\data_stream\Entity\DataStreamInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The LegacySensorApiInterface.
 *
 * An interface that allows data streams to
 * be queried via the Drupal API.
 */
interface LegacySensorApiInterface {

  /**
   * Handle an API Request with the Legacy Listener format.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The DataStream entity.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The HTTP request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The HTTP response.
   */
  public function apiHandleLegacyRequest(DataStreamInterface $stream, Request $request);

}
