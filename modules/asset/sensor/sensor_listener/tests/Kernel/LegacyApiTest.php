<?php

namespace Drupal\Tests\farm_sensor_listener\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\data_stream\Kernel\DataStreamTestBase;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test the Legacy Listener data stream type.
 *
 * @group farm
 */
class LegacyApiTest extends DataStreamTestBase {

  use DataStreamCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'system',
    'field',
    'fraction',
    'state_machine',
    'asset',
    'data_stream',
    'farm_sensor',
    'farm_sensor_listener',
  ];

  /**
   * Legacy listener API path.
   *
   * @var string
   */
  protected $legacyApiPath = '/farm/sensor/listener';

  /**
   * A legacy Listener data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $listener;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['farm_sensor_listener']);
    $this->installSchema('farm_sensor_listener', 'data_stream_data_legacy');

    // Create a listener data stream for testing.
    $this->listener = $this->createDataStreamEntity([
      'type' => 'legacy_listener',
      'private_key' => hash('md5', mt_rand()),
      'public_key' => hash('md5', mt_rand()),
      'public' => FALSE,
    ]);

    // Create 100 data points over the next 100 days.
    $this->mockListenerData($this->listener, 100, \Drupal::time()->getRequestTime());
  }

  /**
   * Test API invalid methods.
   */
  public function testApiInvalidMethods() {

    // Build the path.
    $public_key = $this->listener->get('public_key')->value;
    $uri = $this->legacyApiPath . '/' . $public_key;

    $invalid_methods = [
      Request::METHOD_PUT => 405,
      Request::METHOD_PATCH => 405,
      Request::METHOD_DELETE => 405,
    ];

    // Assert each method is rejected.
    foreach ($invalid_methods as $method => $response_code) {
      $request = Request::create($uri, $method, ['private_key' => $this->listener->getPrivateKey()]);
      $response = $this->processRequest($request);
      $this->assertEqual($response_code, $response->getStatusCode());
    }
  }

  /**
   * Test API GET requests.
   */
  public function testApiGet() {

    // Make a request with invalid public_key.
    // Assert 404.
    $uri = $this->legacyApiPath . '/' . hash('md5', mt_rand());
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEqual(404, $response->getStatusCode());

    // Make a request without private key.
    // Assert 403.
    $public_key = $this->listener->get('public_key')->value;
    $uri = $this->legacyApiPath . '/' . $public_key;
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEqual(403, $response->getStatusCode());

    // Make a request with the private key.
    $request = Request::create($uri, 'GET', ['private_key' => $this->listener->getPrivateKey()]);
    $response = $this->processRequest($request);

    // Assert valid response.
    $this->assertEqual(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEqual(100, count($data));

    // Test the limit param.
    $request = Request::create($uri, 'GET',
      ['private_key' => $this->listener->getPrivateKey(), 'limit' => 10]
    );
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEqual(10, count($data));

    // Test the start and end params.
    // Limit to 15 days of data, which should return 15 results.
    $request_time = \Drupal::time()->getRequestTime();
    $start_time = $request_time + (86400 * 5);
    $end_time = $request_time + (86400 * 20);
    $request = Request::create($uri, 'GET',
      [
        'private_key' => $this->listener->getPrivateKey(),
        'start' => $start_time,
        'end' => $end_time,
      ],
    );
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEqual(15, count($data));
    foreach ($data as $point) {
      $this->assertGreaterThanOrEqual($start_time, $point['timestamp']);
      $this->assertLessThanOrEqual($end_time, $point['timestamp']);
    }

    // Make the sensor public.
    $this->listener->set('public', TRUE)->save();

    // Test that data can be accessed without the private key.
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEqual(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEqual(100, count($data));

    // Add a data point with another name.
    $request_time = \Drupal::time()->getRequestTime();
    $timestamp = $request_time - 86400;
    $test_data = ['timestamp' => $timestamp, 'value' => 200, 'value2' => 300];
    $this->listener->getPlugin()->storageSave($this->listener, $test_data);

    // Make a request for with the "name" query param.
    $request = Request::create($uri, 'GET', ['name' => 'value2']);
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEqual(1, count($data));
  }

  /**
   * Test API POST requests.
   */
  public function testApiPost() {

    // Build the path.
    $public_key = $this->listener->get('public_key')->value;
    $uri = $this->legacyApiPath . '/' . $public_key;

    // Make the stream public. This should not matter for posting data.
    $this->listener->set('public', TRUE)->save();

    // Test data.
    $request_time = \Drupal::time()->getRequestTime();
    $timestamp = $request_time - 86400;
    $test_data = ['timestamp' => $timestamp, 'value' => 200];
    $test_point = new stdClass();
    $test_point->timestamp = $test_data['timestamp'];
    $test_point->value = $test_data['value'];

    // Make a request.
    $request = Request::create($uri, 'POST', [], [], [], [], Json::encode($test_data));
    $response = $this->processRequest($request);
    // Assert that access is denied.
    $this->assertEqual(403, $response->getStatusCode());

    // Post data with a private key.
    $request = $request->duplicate(['private_key' => $this->listener->getPrivateKey()]);
    $response = $this->processRequest($request);
    // Assert success.
    $this->assertEqual(201, $response->getStatusCode());

    // Assert that new data was saved in DB.
    $plugin = $this->listener->getPlugin();
    $data = $plugin->storageGet($this->listener, ['limit' => 1, 'end' => $timestamp]);
    $this->assertEqual(1, count($data));
    $this->assertTrue(in_array($test_point, $data));
  }

}
