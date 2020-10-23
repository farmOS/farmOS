<?php

namespace Drupal\data_stream\Plugin\DataStream;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Drupal\data_stream\DataStreamApiInterface;
use Drupal\data_stream\DataStreamStorageInterface;
use Drupal\data_stream\DataStreamPluginBase;
use Drupal\data_stream\Entity\DataStreamInterface;
use Drupal\data_stream\Traits\DataStreamSqlStorage;
use Drupal\data_stream\Traits\DataStreamPrivateKeyAccess;
use Drupal\jsonapi\Exception\UnprocessableHttpEntityException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * DataStream plugin that provides listener behavior.
 *
 * @DataStream(
 *   id = "listener",
 *   label = @Translation("Listener data stream"),
 * )
 */
class ListenerDataStream extends DataStreamPluginBase implements DataStreamStorageInterface, DataStreamApiInterface {

  use DataStreamSqlStorage;
  use DataStreamPrivateKeyAccess;

  /**
   * A database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a \Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function apiAllowedMethods() {
    return [Request::METHOD_GET, Request::METHOD_POST];
  }

  /**
   * {@inheritdoc}
   */
  public function apiHandleRequest(DataStreamInterface $stream, Request $request) {

    // Get request method.
    $method = $request->getMethod();

    // Handle GET request.
    if ($method == Request::METHOD_GET) {

      // TODO: Allow stream to be public.
      // Bail if private_key is not specified.
      if (!$this->requestHasValidPrivateKey($stream, $request)) {
        throw new AccessDeniedHttpException();
      }

      return $this->apiGet($stream, $request);
    }

    // Handle POST request.
    if ($method == Request::METHOD_POST) {
      if (!$this->requestHasValidPrivateKey($stream, $request)) {
        throw new AccessDeniedHttpException();
      }
      return $this->apiPost($stream, $request);
    }

    // Else bail.
    throw new MethodNotAllowedHttpException($this->apiAllowedMethods());
  }

  /**
   * Handle API GET requests.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The data stream.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The response.
   */
  protected function apiGet(DataStreamInterface $stream, Request $request) {

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

    $data = $this->storageGet($stream, $params);
    return JsonResponse::create($data);
  }

  /**
   * Handle API POST requests.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface $stream
   *   The data stream.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   */
  protected function apiPost(DataStreamInterface $stream, Request $request) {
    $data = Json::decode($request->getContent());
    $success = $this->storageSave($stream, $data);

    if (!$success) {
      throw new BadRequestHttpException();
    }

    return Response::create('', Response::HTTP_CREATED);
  }

}
