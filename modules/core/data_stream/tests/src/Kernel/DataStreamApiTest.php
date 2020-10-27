<?php

namespace Drupal\Tests\data_stream\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test the Listener data stream type.
 *
 * @group farm
 */
class DataStreamApiTest extends DataStreamTestBase {

  use DataStreamCreationTrait;

  /**
   * A Listener data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $listener;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create a listener data stream for testing.
    $this->listener = $this->createDataStreamEntity([
      'type' => 'listener',
      'private_key' => hash('md5', mt_rand()),
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
    $uri = $this->streamApiPath . '/' . $this->listener->uuid();

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

    // Build the path.
    $uri = $this->streamApiPath . '/' . $this->listener->uuid();

    // Make a request.
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);

    // Assert that access is denied.
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
  }

  /**
   * Test API POST requests.
   */
  public function testApiPost() {

    // Build the path.
    $uri = $this->streamApiPath . '/' . $this->listener->uuid();

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

  /**
   * Process a request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   */
  protected function processRequest(Request $request) {
    return $this->container->get('http_kernel')->handle($request);
  }

}
