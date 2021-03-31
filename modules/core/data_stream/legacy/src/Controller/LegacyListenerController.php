<?php

namespace Drupal\farm_sensor_listener\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Route callbacks for the LegacyListener controller.
 */
class LegacyListenerController extends ControllerBase {

  /**
   * Respond to GET or POST requests with Listener data stream public keys.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param string $pub_key
   *   The Listener data stream public key.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function handle(Request $request, string $pub_key) {

    // Load the data stream.
    $data_streams = $this->entityTypeManager()->getStorage('data_stream')->loadByProperties([
      'public_key' => $pub_key,
    ]);

    // Bail if public key is not found.
    if (empty($data_streams)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\data_stream\Entity\DataStreamInterface $data_stream */
    $data_stream = reset($data_streams);

    // Get the data stream plugin.
    $plugin = $data_stream->getPlugin();

    return $plugin->apiHandleRequest($data_stream, $request);
  }

}
