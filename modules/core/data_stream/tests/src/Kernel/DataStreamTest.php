<?php

namespace Drupal\Tests\data_stream\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test the basic data stream type.
 *
 * @group farm
 */
class DataStreamTest extends DataStreamTestBase {

  use DataStreamCreationTrait;

  /**
   * Start time of sensor data.
   *
   * @var int
   */
  protected $startTime;

  /**
   * A basic data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $dataStream;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Save the start time.
    $this->startTime = \Drupal::time()->getRequestTime();

    // Create a basic data stream for testing.
    $this->dataStream = $this->createDataStreamEntity([
      'type' => 'basic',
      'private_key' => hash('md5', mt_rand()),
      'public' => FALSE,
    ]);

    // Create 100 data points over the next 100 days.
    $this->mockBasicData($this->dataStream, 100, $this->startTime);
  }

  /**
   * Test that data is deleted when the data stream is deleted.
   */
  public function testDeleteDataStream() {

    // Save the data stream ID.
    $data_stream_id = $this->dataStream->id();

    // First assert that data exists for the data stream.
    $count = \Drupal::database()->select('data_stream_basic')
      ->condition('id', $data_stream_id)
      ->countQuery()
      ->execute()
      ->fetchfield();
    $this->assertGreaterThan(0, $count);

    // Delete the data stream.
    $this->dataStream->delete();

    // Assert that no data exists for the data stream.
    $count = \Drupal::database()->select('data_stream_basic')
      ->condition('id', $data_stream_id)
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertEquals(0, $count);
  }

  /**
   * Test API invalid methods.
   */
  public function testApiInvalidMethods() {

    // Build the path.
    $uri = $this->buildPath($this->dataStream->uuid());

    $invalid_methods = [
      Request::METHOD_PUT => 405,
      Request::METHOD_PATCH => 405,
      Request::METHOD_DELETE => 405,
    ];

    // Assert each method is rejected.
    foreach ($invalid_methods as $method => $response_code) {
      $request = Request::create($uri, $method, ['private_key' => $this->dataStream->getPrivateKey()]);
      $response = $this->processRequest($request);
      $this->assertEquals($response_code, $response->getStatusCode());
    }
  }

  /**
   * Test API GET requests.
   */
  public function testApiGet() {

    // Build the path.
    $uri = $this->buildPath($this->dataStream->uuid());

    // Make a request.
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);

    // Assert that access is denied.
    $this->assertEquals(403, $response->getStatusCode());

    // Make a request with the private key.
    $request = Request::create($uri, 'GET', ['private_key' => $this->dataStream->getPrivateKey()]);
    $response = $this->processRequest($request);

    // Assert valid response.
    $this->assertEquals(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEquals(100, count($data));

    // Test the limit param.
    $request = Request::create($uri, 'GET',
      ['private_key' => $this->dataStream->getPrivateKey(), 'limit' => 10]
    );
    $response = $this->processRequest($request);
    $data = Json::decode($response->getContent());
    $this->assertEquals(10, count($data));

    // Test the start and end params.
    // Limit to 15 days of data, which should return 15 results.
    $end_time = $this->startTime + (86400 * 14);
    $request = Request::create($uri, 'GET',
      [
        'private_key' => $this->dataStream->getPrivateKey(),
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
    $this->dataStream->set('public', TRUE)->save();

    // Test that data can be accessed without the private key.
    $request = Request::create($uri, 'GET');
    $response = $this->processRequest($request);
    $this->assertEquals(200, $response->getStatusCode());
    $data = Json::decode($response->getContent());
    $this->assertEquals(100, count($data));
  }

  /**
   * Test API POST requests.
   */
  public function testApiPost() {

    // Build the path.
    $uri = $this->buildPath($this->dataStream->uuid());

    // Make the stream public. This should not matter for posting data.
    $this->dataStream->set('public', TRUE)->save();

    // Test data.
    $timestamp = $this->startTime - 86400;
    $name = $this->dataStream->label();
    $test_data = ['timestamp' => $timestamp, $name => 200];
    $test_point = new \stdClass();
    $test_point->timestamp = $test_data['timestamp'];
    $test_point->{$name} = 200;

    // Make a request.
    $request = Request::create($uri, 'POST', [], [], [], [], Json::encode($test_data));
    $response = $this->processRequest($request);
    // Assert that access is denied.
    $this->assertEquals(403, $response->getStatusCode());

    // Post data with a private key.
    $request = $request->duplicate(['private_key' => $this->dataStream->getPrivateKey()]);
    $response = $this->processRequest($request);
    // Assert success.
    $this->assertEquals(201, $response->getStatusCode());

    // Assert that new data was saved in DB.
    $plugin = $this->dataStream->getPlugin();
    $data = $plugin->storageGet($this->dataStream, ['limit' => 1, 'end' => $timestamp]);
    $this->assertEquals(1, count($data));
    $this->assertTrue(in_array($test_point, $data));

    // Try posting with non-numeric data.
    $bad_data_point = $test_point;
    $bad_data_point->value = "string";
    $request = Request::create($uri, 'POST', ['private_key' => $this->dataStream->getPrivateKey()], [], [], [], Json::encode($bad_data_point));
    $response = $this->processRequest($request);
    // Assert successful response.
    $this->assertEquals(201, $response->getStatusCode());

    // Assert that new data WAS NOT saved in DB.
    $plugin = $this->dataStream->getPlugin();
    $data = $plugin->storageGet($this->dataStream, ['limit' => 5, 'end' => $timestamp]);
    $this->assertTrue(!in_array($bad_data_point, $data));
  }

  /**
   * Helper function to build the path to data stream data.
   *
   * @param string $uuid
   *   The UUID to include.
   *
   * @return string
   *   The path.
   */
  protected function buildPath(string $uuid) {
    return '/api/data_stream/' . $uuid . '/data';
  }

}
