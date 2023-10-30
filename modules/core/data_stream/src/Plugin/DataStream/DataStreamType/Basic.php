<?php

namespace Drupal\data_stream\Plugin\DataStream\DataStreamType;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Database\Connection;
use Drupal\data_stream\DataStreamApiInterface;
use Drupal\data_stream\DataStreamEventDispatcherInterface;
use Drupal\data_stream\DataStreamStorageInterface;
use Drupal\data_stream\Entity\DataStreamInterface;
use Drupal\data_stream\Event\DataStreamEvent;
use Drupal\data_stream\Traits\DataStreamPrivateKeyAccess;
use Drupal\fraction\Fraction;
use Drupal\jsonapi\Exception\UnprocessableHttpEntityException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Provides the basic data stream type.
 *
 * @DataStreamType(
 *   id = "basic",
 *   label = @Translation("Basic"),
 * )
 */
class Basic extends DataStreamTypeBase implements DataStreamStorageInterface, DataStreamApiInterface, DataStreamEventDispatcherInterface {

  use DataStreamPrivateKeyAccess;

  /**
   * A database connection.
   *
   * @var \Drupal\Core\Database\Connection
   *
   * @see DataStreamSqlStorage
   */
  protected $connection;

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Database table.
   *
   * @var string
   *
   * @see DataStreamSqlStorage
   */
  protected $tableName = 'data_stream_basic';

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
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition, Connection $connection, EventDispatcherInterface $event_dispatcher, TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->connection = $connection;
    $this->eventDispatcher = $event_dispatcher;
    $this->time = $time;
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
      $container->get('event_dispatcher'),
      $container->get('datetime.time'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSupportedEvents(): array {
    return [
      DataStreamEvent::DATA_RECEIVE => [
        'name' => $this->t('The name of the data stream value.'),
        'value' => $this->t('The raw value.'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = [];

    // Save the table name.
    $data_table = 'data_stream_basic';

    // Describe the {data_stream_basic} table.
    $data[$data_table]['table']['group'] = $this->t('Basic data stream data');
    $data[$data_table]['table']['base'] = [
      'title' => $this->t('Basic data stream data'),
      'help' => $this->t('Data provided by basic data streams.'),
    ];

    // Data stream ID.
    $data[$data_table]['id'] = [
      'title' => $this->t('Data stream ID'),
      'help' => $this->t('ID of the data stream entity.'),
      'relationship' => [
        'base' => 'data_stream_data',
        'base_field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Data stream entity'),
      ],
    ];

    // Timestamp.
    $data[$data_table]['timestamp'] = [
      'title' => $this->t('Timestamp'),
      'help' => $this->t('Timestamp of the sensor reading.'),
      'field' => [
        'id' => 'date',
        'click sortable' => TRUE,
      ],
      'sort' => [
        'id' => 'date',
      ],
      'filter' => [
        'id' => 'date',
      ],
    ];

    // Value numerator.
    $data[$data_table]['value_numerator'] = [
      'title' => $this->t('Value numerator'),
      'help' => $this->t('The stored numerator value of the data stream reading.'),
      'field' => [
        'id' => 'numeric',
        'click sortable' => TRUE,
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'sort',
      ],
    ];

    // Value denominator.
    $data[$data_table]['value_denominator'] = [
      'title' => $this->t('Value denominator'),
      'help' => $this->t('The stored denominator value of the data stream reading.'),
      'field' => [
        'id' => 'numeric',
        'click sortable' => TRUE,
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'sort' => [
        'id' => 'sort',
      ],
    ];

    // Create a new decimal column with fraction decimal handlers.
    $fraction_fields = [
      'numerator' => 'value_numerator',
      'denominator' => 'value_denominator',
    ];
    $data[$data_table]['value'] = [
      'title' => $this->t('Value'),
      'help' => $this->t('Decimal equivalent of the data stream reading.'),
      'real field' => 'value_numerator',
      'field' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
        'click sortable' => TRUE,
      ],
      'sort' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
      ],
      'filter' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
      ],
    ];

    // Add a basic_data relationship to the data_stream_data table that
    // references the data_stream_basic table.
    $data['data_stream_data']['basic_data'] = [
      'title' => $this->t('Basic data'),
      'help' => $this->t('Basic data stream data.'),
      'relationship' => [
        'base' => 'data_stream_basic',
        'base field' => 'id',
        'field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Basic data'),
      ],
    ];

    return $data;
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

      // Bail if the sensor is not public and no private_key is provided.
      if (!$stream->isPublic() && !$this->requestHasValidPrivateKey($stream, $request)) {
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
    return new JsonResponse($data);
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

    return new Response('', Response::HTTP_CREATED);
  }

  /**
   * {@inheritdoc}
   */
  public function storageGet(DataStreamInterface $stream, array $params) {
    return $this->storageGetMultiple([$stream], $params);
  }

  /**
   * Get data from multiple data streams.
   *
   * @param \Drupal\data_stream\Entity\DataStreamInterface[] $data_streams
   *   Array of data streams.
   * @param array $params
   *   Parameters.
   *
   * @return array
   *   Array of data.
   */
  public function storageGetMultiple(array $data_streams, array $params) {

    // Bail if no data streams are specified.
    if (empty($data_streams)) {
      return [];
    }

    // Collect data stream ids.
    $data_stream_ids = array_map(function ($data_stream) {
      return $data_stream->id();
    }, $data_streams);

    // Query for data stream data.
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = $this->connection->select($this->tableName, 'd');
    $query->fields('d', ['timestamp', 'value_numerator', 'value_denominator']);
    $query->leftJoin('data_stream_data', 'dsd', 'd.id = dsd.id');
    $query->addField('dsd', 'name');

    // Limit to the specified data streams.
    $query->condition('d.id', $data_stream_ids, 'IN');

    if (isset($params['start']) && is_numeric($params['start'])) {
      $query->condition('d.timestamp', $params['start'], '>=');
    }

    if (isset($params['end']) && is_numeric($params['end'])) {
      $query->condition('d.timestamp', $params['end'], '<=');
    }

    if (isset($params['name'])) {
      $operator = is_array($params['name']) ? 'IN' : '=';
      $query->condition('dsd.name', $params['name'], $operator);
    }

    $query->orderBy('d.timestamp', 'DESC');

    $offset = 0;
    if (isset($params['offset']) && is_numeric($params['offset'])) {
      $offset = $params['offset'];
    }

    if (isset($params['limit']) && is_numeric($params['limit'])) {
      $query->range($offset, $params['limit']);
    }

    $result = $query->execute();

    // Build an array of data.
    $data = [];
    foreach ($result as $row) {

      // If name or timestamp are empty, skip.
      if (empty($row->timestamp)) {
        continue;
      }

      // Convert the value numerator and denominator to a decimal.
      $fraction = new Fraction($row->value_numerator, $row->value_denominator);
      $value = $fraction->toDecimal(0, TRUE);

      // Create a data object for the sensor value.
      $point = new \stdClass();
      $point->timestamp = $row->timestamp;
      $point->{$row->name} = $value;
      $data[] = $point;
    }

    // Return the data.
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSave(DataStreamInterface $stream, array $data) {

    // If the data is an array of multiple data points, iterate over each and
    // recursively process.
    if (is_array(reset($data))) {
      foreach ($data as $point) {
        $this->storageSave($stream, $point);
      }
      return TRUE;
    }

    // Save a timestamp.
    $timestamp = NULL;

    // If a timestamp is provided, ensure that it is in UNIX timestamp format.
    if (!empty($data['timestamp'])) {

      // If the timestamp is numeric, we're good!
      if (is_numeric($data['timestamp'])) {
        $timestamp = $data['timestamp'];
      }

      // Otherwise, try converting it from a string. If that doesn't work, we
      // throw it out and fall back on REQUEST_TIME set above.
      else {
        $strtotime = strtotime($data['timestamp']);
        if (!empty($strtotime)) {
          $timestamp = $strtotime;
        }
      }
    }

    // Generate a timestamp from the request time. This will only be used if a
    // timestamp is not provided in the JSON data.
    if (empty($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Iterate over the JSON properties.
    foreach ($data as $key => $value) {

      // If the key does not match the data stream name, skip it.
      if ($key !== $stream->label()) {
        continue;
      }

      // If the value is not numeric, skip it.
      if (!is_numeric($value)) {
        continue;
      }

      // Create a row to store in the database;.
      $row = [
        'id' => $stream->id(),
        'timestamp' => $timestamp,
      ];

      // Convert the value to a fraction.
      $fraction = Fraction::createFromDecimal($value);
      $row['value_numerator'] = $fraction->getNumerator();
      $row['value_denominator'] = $fraction->getDenominator();

      // Enter the reading into the database.
      $this->connection->insert($this->tableName)
        ->fields($row)
        ->execute();

      // Dispatch a data stream receive event.
      $context = [
        'value' => $value,
        'name' => $stream->label(),
      ];
      $event = new DataStreamEvent($stream, $context);
      $this->eventDispatcher->dispatch($event, DataStreamEvent::DATA_RECEIVE);
    }

    return TRUE;
  }

}
