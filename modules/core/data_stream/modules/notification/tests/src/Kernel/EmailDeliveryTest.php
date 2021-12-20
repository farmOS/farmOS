<?php

namespace Drupal\Tests\data_stream_notification\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Test\AssertMailTrait;
use Drupal\data_stream_notification\Entity\DataStreamNotification;
use Drupal\Tests\data_stream\Kernel\DataStreamTestBase;
use Drupal\Tests\data_stream\Traits\DataStreamCreationTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests email notification delivery.
 *
 * @group farm
 */
class EmailDeliveryTest extends DataStreamTestBase {

  use AssertMailTrait;
  use DataStreamCreationTrait;

  /**
   * The notification delivery manager interface.
   *
   * @var \Drupal\data_stream_notification\NotificationDeliveryManagerInterface
   */
  protected $deliveryManager;

  /**
   * A basic data stream.
   *
   * @var \Drupal\data_stream\Entity\DataStreamInterface
   */
  protected $dataStream;

  /**
   * The data stream notification.
   *
   * @var \Drupal\data_stream_notification\Entity\DataStreamNotification
   */
  protected $dataStreamNotification;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'data_stream',
    'data_stream_notification',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Get the notification delivery manager.
    $this->deliveryManager = $this->container->get('plugin.manager.data_stream_notification_delivery');

    // Create a basic data stream for testing.
    $this->dataStream = $this->createDataStreamEntity([
      'type' => 'basic',
      'private_key' => hash('md5', mt_rand()),
      'public' => FALSE,
    ]);

    $this->dataStreamNotification = DataStreamNotification::create([
      'id' => 'test',
      'label' => 'Test',
      'data_stream' => $this->dataStream->id(),
      'activation_threshold' => 1,
      'deactivation_threshold' => 1,
      'condition_operator' => 'and',
      'condition' => [
        [
          'type' => 'numeric',
          'condition' => '>',
          'threshold' => 0,
        ],
        [
          'type' => 'numeric',
          'condition' => '<',
          'threshold' => 20,
        ],
      ],
      'delivery_interval' => 1,
      'delivery' => [
        [
          'type' => 'email',
          'email' => [
            'test@test.com',
          ],
        ],
      ],
    ]);
    $this->dataStreamNotification->save();
  }

  /**
   * Tests the email delivery plugin in isolation.
   */
  public function testEmailDeliveryPlugin() {

    // Get the first configured email delivery plugin.
    $collections = $this->dataStreamNotification->getPluginCollections();
    $email_delivery = $collections['delivery']->get(0);

    // Build a list of condition summaries to test against.
    $condition_summaries = array_map(function ($condition) {
      return $condition->summary();
    }, iterator_to_array($collections['condition']));

    // Test with valid values.
    $email_delivery->setContextValue('value', 5);
    $email_delivery->setContextValue('data_stream', $this->dataStream);
    $email_delivery->setContextValue('data_stream_notification', $this->dataStreamNotification);
    $email_delivery->setContextValue('condition_summaries', $condition_summaries);
    $this->assertTrue($email_delivery->execute());

    // Ensure that there is one email in the captured emails array.
    $this->assertCount(1, $this->getmails(), 'One email was captured.');

    $subject = $this->dataStreamNotification->label() . ' notification for data stream: ' . $this->dataStream->label();
    $this->assertMail('subject', $subject);
    $this->assertMailString('body', $this->dataStream->toUrl()->setAbsolute()->toString(), 1);
    $this->assertMailString('body', $this->dataStream->label(), 1);
    $this->assertMailString('body', "Actual value: 5", 1);

    // Assert that each summary was included.
    foreach ($condition_summaries as $summary) {
      $this->assertMailString('body', $summary, 1);
    }

    // Test when value is non-numeric.
    $email_delivery->setContextValue('value', 'string');
    $email_delivery->setContextValue('data_stream', $this->dataStream);
    $email_delivery->setContextValue('data_stream_notification', $this->dataStreamNotification);
    $email_delivery->setContextValue('condition_summaries', $condition_summaries);
    $this->assertFalse($email_delivery->execute());
    $this->assertCount(1, $this->getmails(), 'One email was captured.');

    // Test when there is no "value" in the context.
    $email_delivery->setContextValue('value', NULL);
    $email_delivery->setContextValue('data_stream', $this->dataStream);
    $email_delivery->setContextValue('data_stream_notification', $this->dataStreamNotification);
    $email_delivery->setContextValue('condition_summaries', $condition_summaries);
    $this->assertFalse($email_delivery->execute());
    $this->assertCount(1, $this->getmails(), 'One email was captured.');
  }

  /**
   * Integration test of the email delivery plugin.
   */
  public function testEmailDeliveryIntegration() {

    // Get the first configured email delivery plugin.
    $collections = $this->dataStreamNotification->getPluginCollections();

    // Build a list of condition summaries to test against.
    $condition_summaries = array_map(function ($condition) {
      return $condition->summary();
    }, iterator_to_array($collections['condition']));

    // Build the path.
    $uuid = $this->dataStream->uuid();
    $uri  = "/api/data_stream/$uuid/data";

    // Get the correct name for test data.
    $name = $this->dataStream->label();

    // Post data within the condition threshold.
    $test_data = [$name => 10];
    $request = Request::create($uri, 'POST', ['private_key' => $this->dataStream->getPrivateKey()], [], [], [], Json::encode($test_data));
    $response = $this->processRequest($request);
    $this->assertEquals(201, $response->getStatusCode());

    // Ensure that there is one email in the captured emails array.
    $this->assertCount(1, $this->getmails(), 'One email was captured.');

    $subject = $this->dataStreamNotification->label() . ' notification for data stream: ' . $this->dataStream->label();
    $this->assertMail('subject', $subject);
    $this->assertMailString('body', $this->dataStream->toUrl()->setAbsolute()->toString(), 1);
    $this->assertMailString('body', $this->dataStream->label(), 1);
    $this->assertMailString('body', "Actual value: 10", 1);

    // Assert that each summary was included.
    foreach ($condition_summaries as $summary) {
      $this->assertMailString('body', $summary, 1);
    }
  }

}
