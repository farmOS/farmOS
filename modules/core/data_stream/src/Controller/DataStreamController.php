<?php

namespace Drupal\data_stream\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\data_stream\DataStreamApiInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Route callbacks for the DataStream controller.
 */
class DataStreamController extends ControllerBase {

  /**
   * Respond to GET or POST requests referencing Data Streams by UUID.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param string $uuid
   *   The DataStream UUID.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function dataStream(Request $request, string $uuid) {

    // Load the data stream.
    $data_streams = $this->entityTypeManager()->getStorage('data_stream')->loadByProperties([
      'uuid' => $uuid,
    ]);

    // Bail if UUID is not found.
    if (empty($data_streams)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\data_stream\Entity\DataStreamInterface $data_stream */
    $data_stream = reset($data_streams);

    // Get the data stream plugin.
    $plugin = $data_stream->getPlugin();

    // Bail if the plugin does not handle API requests.
    if (!$plugin instanceof DataStreamApiInterface) {
      throw new MethodNotAllowedHttpException([]);
    }

    // Get the request method.
    $method = $request->getMethod();

    // Bail if the plugin does not allow the method.
    $allowed_methods = $plugin->apiAllowedMethods();
    if (!in_array($method, $allowed_methods)) {
      throw new MethodNotAllowedHttpException($allowed_methods);
    }

    return $plugin->apiHandleRequest($data_stream, $request);
  }

}
