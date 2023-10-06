<?php

namespace Drupal\farm_sensor\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\data_stream\DataStreamTypeManager;
use Drupal\jsonapi\Exception\UnprocessableHttpEntityException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles requests for basic data streams associated with a sensor.
 */
class SensorDataController extends ControllerBase {

  /**
   * The basic data stream plugin.
   *
   * @var \Drupal\data_stream\Plugin\DataStream\DataStreamType\Basic
   */
  protected $basicDataStream;

  /**
   * SensorDataController constructor.
   *
   * @param \Drupal\data_stream\DataStreamTypeManager $data_stream_type_manager
   *   The data stream type manager.
   */
  public function __construct(DataStreamTypeManager $data_stream_type_manager) {
    $this->basicDataStream = $data_stream_type_manager->createInstance('basic');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.data_stream_type')
    );
  }

  /**
   * Respond to GET or POST requests referencing sensor assets by UUID.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param string $uuid
   *   The sensor asset UUID.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   */
  public function uuid(Request $request, string $uuid) {

    // Load the sensor asset.
    $sensor_assets = $this->entityTypeManager()
      ->getStorage('asset')
      ->loadByProperties([
        'type' => 'sensor',
        'uuid' => $uuid,
      ]);

    // Bail if UUID is not found.
    if (empty($sensor_assets)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = reset($sensor_assets);
    return $this->handleAssetRequest($asset, $request);
  }

  /**
   * Helper function to handle the request once the asset has been loaded.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   */
  protected function handleAssetRequest(AssetInterface $asset, Request $request) {

    /** @var \Drupal\data_stream\Entity\DataStreamInterface[] $data_streams */
    $data_streams = $asset->get('data_stream')->referencedEntities();
    $basic_data_streams = array_filter($data_streams, function ($data_stream) {
      return $data_stream->bundle() === 'basic';
    });

    // Get request method.
    $method = $request->getMethod();
    switch ($method) {
      case Request::METHOD_GET:

        // Bail if the sensor is not public and no private_key is provided.
        if (!$asset->get('public')->value && !$this->requestHasValidPrivateKey($asset, $request)) {
          throw new AccessDeniedHttpException();
        }

        $params = $request->query->all();
        $max_limit = 100000;
        $limit = $max_limit;
        if (isset($params['limit'])) {
          $limit = $params['limit'];

          // Bail if more than the max is requested.
          // Only allow 100k max data points to prevent exhausting PHP's memory,
          // which is a potential DDoS vector.
          if ($limit > $max_limit) {
            throw new UnprocessableHttpEntityException();
          }
        }
        $params['limit'] = $limit;

        $data = $this->basicDataStream->storageGetMultiple($basic_data_streams, $params);
        return new JsonResponse($data);

      case Request::METHOD_POST:

        // Bail if no private_key is provided.
        if (!$this->requestHasValidPrivateKey($asset, $request)) {
          throw new AccessDeniedHttpException();
        }

        // Load the data.
        $data = Json::decode($request->getContent());

        // Check for new named values.
        $unique_names = $this->getUniqueNamedValues($data);
        $existing_names = array_map(function ($data_stream) {
          return $data_stream->label();
        }, $basic_data_streams);

        // Create new data streams for new named values.
        foreach ($unique_names as $name) {
          if (!in_array($name, $existing_names)) {
            $basic_data_streams[] = $this->createDataStream($asset, $name);
          }
        }

        // Allow each data stream to process the data.
        foreach ($basic_data_streams as $data_stream) {
          $this->basicDataStream->storageSave($data_stream, $data);
        }
        return new Response('', Response::HTTP_CREATED);
    }

    // Else raise error.
    throw new MethodNotAllowedHttpException($this->basicDataStream->apiAllowedMethods());
  }

  /**
   * Helper function to determine if the request provides a correct private_key.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return bool
   *   If the request has access.
   */
  protected function requestHasValidPrivateKey(AssetInterface $asset, Request $request) {
    $private_key = $asset->get('private_key')->value;
    return $private_key == $request->get('private_key', '');
  }

  /**
   * Helper function to extract unique named values from the data payload.
   *
   * @param array $data
   *   The submitted data.
   *
   * @return array
   *   Array of unique names.
   */
  protected function getUniqueNamedValues(array $data): array {

    // Start an array of names.
    $names = [];

    // If the data is an array of multiple data points, iterate over each and
    // recursively process.
    if (is_array(reset($data))) {
      foreach ($data as $point) {
        $names = array_unique(array_merge($names, $this->getUniqueNamedValues($point)));
      }
      return $names;
    }

    // Iterate over the JSON properties to get each name.
    foreach ($data as $key => $value) {
      if ($key !== 'timestamp') {
        $names[] = $key;
      }
    }

    return array_unique($names);
  }

  /**
   * Helper function to create a new basic data stream associated with a sensor.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The sensor asset.
   * @param string $name
   *   The data stream name.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The new data stream.
   */
  protected function createDataStream(AssetInterface $asset, string $name) {

    // Create new data stream.
    $new_data_stream = $this->entityTypeManager()->getStorage('data_stream')->create([
      'type' => 'basic',
      'name' => $name,
    ]);
    $new_data_stream->save();

    // Assign to the host sensor asset.
    /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $data_stream_field */
    $data_stream_field = $asset->get('data_stream');
    $data_stream_field->appendItem($new_data_stream);
    $asset->save();

    return $new_data_stream;
  }

}
