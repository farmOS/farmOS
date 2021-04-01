<?php

namespace Drupal\data_stream\Traits;

use Drupal\data_stream\Entity\DataStreamInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * A trait for using the Data Stream private key to validate access.
 */
trait DataStreamPrivateKeyAccess {

  /**
   * Helper function to determine if the request provides a correct private_key.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The data stream.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return bool
   *   If the request has access.
   */
  protected function requestHasValidPrivateKey(DataStreamInterface $stream, Request $request) {

    // Get the data stream private key.
    $private_key = $stream->getPrivateKey();

    // Check if the private key matches.
    if ($private_key == $request->get('private_key', '')) {
      return TRUE;
    }

    return FALSE;
  }

}
