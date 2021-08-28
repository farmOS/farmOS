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
      'activation_threshold' => 1,
      'deactivation_threshold' => 1,
      'condition_operator' => 'or',
      'condition' => [
        [
          'type' => 'numeric',
          'condition' => '>',
          'threshold' => 0,
        ],
      ],
      'delivery_interval' => 1,
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
   * Test the notification activation and deactivation thresholds.
   */
  public function testNotificationThresholds() {

    // Configure the thresholds to 2 for simpler testing.
    $this->notification->set('activation_threshold', 2);
    $this->notification->set('deactivation_threshold', 2);
    $this->notification->save();

    // 1. Meet conditions. Set activate_count to 1. No notification.
    $response = $this->postTestData(100);
    $this->assertEquals(201, $response->getStatusCode());

    // 2. Do not meet conditions. Set activate_count to 0. No notification.
    $response = $this->postTestData(0);
    $this->assertEquals(201, $response->getStatusCode());

    // 3. Meet conditions. Set activate_count to 1. No notification.
    $response = $this->postTestData(100);
    $this->assertEquals(201, $response->getStatusCode());

    // 4. Meet conditions. Set activate_count to 2.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // 5. Do not meet conditions. Set deactivate_count to 1. No notification.
    $response = $this->postTestData(0);
    $this->assertEquals(201, $response->getStatusCode());

    // 6. Meet conditions. Set deactivate_count to 0.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // 7. Do not meet conditions. Set deactivate_count to 1. No notification.
    $response = $this->postTestData(0);
    $this->assertEquals(201, $response->getStatusCode());

    // 8. Do not meet conditions. Set deactivate_count to 2. No notification.
    $response = $this->postTestData(0);
    $this->assertEquals(201, $response->getStatusCode());

    // 9. Meet conditions. Set activate_count to 1. No notification.
    $response = $this->postTestData(100);
    $this->assertEquals(201, $response->getStatusCode());
  }

  /**
   * Test the notification delivery interval.
   */
  public function testNotificationDeliveryInterval() {

    // Configure the delivery interval to 2 for testing.
    // Notification should only happen for every other success.
    $this->notification->set('delivery_interval', 2);
    $this->notification->save();

    // 1. Meet conditions. Set activate_count to 1.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // 2. Meet conditions. Set activate_count to 2. No notification.
    $response = $this->postTestData(100);
    $this->assertEquals(201, $response->getStatusCode());

    // 3. Meet conditions. Set activate_count to 3.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // Change the delivery interval to 1.
    // Notifications should happen on each success.
    $this->notification->set('delivery_interval', 1);
    $this->notification->save();

    // 4. Meet conditions. Set activate_count to 4.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // 5. Meet conditions. Set activate_count to 5.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // Change the delivery interval to 0.
    // Notifications should only happen on the first success.
    $this->notification->set('delivery_interval', 0);
    $this->notification->save();

    // 6. Meet conditions. Set activate_count to 6.
    // Assert that the notification delivery was executed.
    $response = $this->postTestData(100);
    $this->assertEquals(201, $response->getStatusCode());

    // 7. Do not meet conditions. Set activate_count to 0. No notification.
    $response = $this->postTestData(0);
    $this->assertEquals(201, $response->getStatusCode());

    // 8. Meet conditions. Set activate_count to 1.
    $response = $this->postTestData(100);
    $this->assertEquals(299, $response->getStatusCode());

    // 9. Meet conditions. Set activate_count to 2. No notification.
    $response = $this->postTestData(100);
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
