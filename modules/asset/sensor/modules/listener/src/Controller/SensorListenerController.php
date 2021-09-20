<?php

namespace Drupal\farm_sensor_listener\Controller;

use Drupal\farm_sensor\Controller\SensorDataController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Route callbacks for the legacy sensor listener controller.
 */
class SensorListenerController extends SensorDataController {

  /**
   * Respond to GET or POST requests referencing sensor assets by public_key.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param string $public_key
   *   The sensor asset public_key.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   */
  public function publicKey(Request $request, string $public_key) {

    // Load the sensor asset.
    $sensor_assets = $this->entityTypeManager()
      ->getStorage('asset')
      ->loadByProperties([
        'type' => 'sensor',
        'public_key' => $public_key,
      ]);

    // Bail if the public_key is not found.
    if (empty($sensor_assets)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = reset($sensor_assets);
    return $this->handleAssetRequest($asset, $request);
  }

}
