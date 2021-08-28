<?php

namespace Drupal\Tests\data_stream_notification\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\data_stream_notification\Entity\DataStreamNotification;
use Drupal\Tests\data_stream\Kernel\DataStreamTestBase;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test functionality of data stream notification execution.
 *
 * @group farm
 */
class NotificationTest extends DataStreamTestBase {

  use DataStreamCreationTrait;

  /**
   * A basic data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $dataStream;

  /**
   * The notification to test.
   *
   * @var \Drupal\data_stream_notification\Entity\DataStreamNotification
   */
  protected $notification;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'data_stream',
    'data_stream_notification',
    'data_stream_notification_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('data_stream_notification');

    // Create a basic data stream for testing.
    $this->dataStream = $this->createDataStreamEntity([
      'type' => 'basic',
      'private_key' => hash('md5', mt_rand()),
      'public' => FALSE,
    ]);

    $this->notification = DataStreamNotification::create([
      'id' => 'test',
      'label' => 'Test',
      'data_stream' => $this->dataStream->id(),
      'condition_operator' => 'or',
      'condition' => [
        [
          'type' => 'numeric',
          'condition' => '>',
          'threshold' => 0,
        ],
      ],
      'delivery' => [
        ['type' => 'error'],
      ],
    ]);
    $this->notification->save();
  }

  /**
   * Test execution of notifications.
   */
  public function testNotificationExecution() {

    // Post data that should not trigger the notification.
    $response = $this->postTestData(0);
    // Assert that no notification delivery was executed.
    $this->assertEquals(201, $response->getStatusCode());

    // Post data above the condition threshold.
    $response = $this->postTestData(100);
    // Assert that the notification delivery was executed.
    $this->assertEquals(299, $response->getStatusCode());
    $this->assertStringContainsString("Data stream value triggered a notification exception: 100", $response->getContent());

    // Don't configure the data stream with the notification.
    $this->notification->set('data_stream', 0);
    $this->notification->save();

    // Post data above the condition threshold.
    $response = $this->postTestData(100);
    // Assert that no notification delivery was executed.
    $this->assertEquals(201, $response->getStatusCode());
  }

  /**
   * Helper function to post test data to a data stream.
   *
   * @param float $value
   *   The value to post.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response returned.
   */
  protected function postTestData(float $value): Response {

    // Build the path.
    $uuid = $this->dataStream->uuid();
    $uri  = "/api/data_stream/$uuid/data";

    // Make the stream public. This should not matter for posting data.
    $this->dataStream->set('public', TRUE)->save();

    // Get the correct name for test data.
    $name = $this->dataStream->label();

    // Build the request.
    $test_data = [$name => $value];
    $request = Request::create($uri, 'POST', ['private_key' => $this->dataStream->getPrivateKey()], [], [], [], Json::encode($test_data));

    // Return the response.
    return $this->processRequest($request);
  }

}
