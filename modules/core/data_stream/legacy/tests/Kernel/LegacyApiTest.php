<?php

namespace Drupal\Tests\farm_sensor_listener\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\data_stream\Kernel\DataStreamTestBase;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
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
  protected static $modules = [
    'system',
    'entity',
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
   * Start time of sensor data.
   *
   * @var int
   */
  protected $startTime;

  /**
   * A legacy Listener data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $listener;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installConfig(['farm_sensor_listener']);
    $this->installSchema('farm_sensor_listener', 'data_stream_legacy');

    // Save the start time.
    $this->startTime = \Drupal::time()->getRequestTime();

    // Create a listener data stream for testing.
    $this->listener = $this->createDataStreamEntity([
      'type' => 'legacy_listener',
      'private_key' => hash('md5', mt_rand()),
      'public_key' => hash('md5', mt_rand()),
      'public' => FALSE,
    ]);

    // Create 100 data points over the next 100 days.
    $this->mockBasicData($this->listener, 100, $this->startTime);
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
      $this->assertEquals($response_code, $response->getStatusCode());
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
    $this->assertEquals(404, $response->getStatusCode());

    // Make a request without private key.
    // Assert 403.
    $public_key = $this->listener->get('public_key')->value;
    $uri = $this->legacyApiPath . '/' . $public_key;
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEquals(403, $response->getStatusCode());

    // Make a request with the private key.
    $request = Request::create($uri, 'GET', ['private_key' => $this->listener->getPrivateKey()]);
    $response = $this->processRequest($request);

    // Assert valid response.
    $this->assertEquals(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEquals(100, count($data));

    // Test the limit param.
    $request = Request::create($uri, 'GET',
      ['private_key' => $this->listener->getPrivateKey(), 'limit' => 10]
    );
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEquals(10, count($data));

    // Test the start and end params.
    // Limit to 15 days of data, which should return 15 results.
    $end_time = $this->startTime + (86400 * 14);
    $request = Request::create($uri, 'GET',
      [
        'private_key' => $this->listener->getPrivateKey(),
        'start' => $this->startTime,
        'end' => $end_time,
      ],
    );
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEquals(15, count($data));
    foreach ($data as $point) {
      $this->assertGreaterThanOrEqual($this->startTime, $point['timestamp']);
      $this->assertLessThanOrEqual($end_time, $point['timestamp']);
    }

    // Make the sensor public.
    $this->listener->set('public', TRUE)->save();

    // Test that data can be accessed without the private key.
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEquals(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEquals(100, count($data));

    // Add a data point with another name.
    $request_time = \Drupal::time()->getRequestTime();
    $timestamp = $request_time - 86400;
    $test_data = ['timestamp' => $timestamp, 'value' => 200, 'value2' => 300];
    $this->listener->getPlugin()->storageSave($this->listener, $test_data);

    // Make a request for with the "name" query param.
    $request = Request::create($uri, 'GET', ['name' => 'value2']);
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEquals(1, count($data));
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
    $timestamp = $this->startTime - 86400;
    $test_data = ['timestamp' => $timestamp, 'value' => 200];
    $test_point = new \stdClass();
    $test_point->timestamp = $test_data['timestamp'];
    $test_point->value = $test_data['value'];

    // Make a request.
    $request = Request::create($uri, 'POST', [], [], [], [], Json::encode($test_data));
    $response = $this->processRequest($request);
    // Assert that access is denied.
    $this->assertEquals(403, $response->getStatusCode());

    // Post data with a private key.
    $request = $request->duplicate(['private_key' => $this->listener->getPrivateKey()]);
    $response = $this->processRequest($request);
    // Assert success.
    $this->assertEquals(201, $response->getStatusCode());

    // Assert that new data was saved in DB.
    $plugin = $this->listener->getPlugin();
    $data = $plugin->storageGet($this->listener, ['limit' => 1, 'end' => $timestamp]);
    $this->assertEquals(1, count($data));
    $this->assertTrue(in_array($test_point, $data));
  }

}
