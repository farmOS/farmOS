<?php

namespace Drupal\data_stream;

use Drupal\data_stream\Entity\DataStreamInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The DataStreamApiInterface.
 *
 * An interface that allows data streams to
 * be queried via the Drupal API.
 */
interface DataStreamApiInterface {

  /**
   * Determine allowed HTTP methods.
   *
   * @return array
   *   Allowed methods.
   */
  public function apiAllowedMethods();

  /**
   * Handle an API Request.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The DataStream entity.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The HTTP request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The HTTP response.
   */
  public function apiHandleRequest(DataStreamInterface $stream, Request $request);

}
