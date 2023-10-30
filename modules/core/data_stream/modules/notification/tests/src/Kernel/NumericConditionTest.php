<?php

namespace Drupal\Tests\data_stream_notification\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the numeric notification condition.
 *
 * @group farm
 */
class NumericConditionTest extends KernelTestBase {

  /**
   * The notification condition manager interface.
   *
   * @var \Drupal\data_stream_notification\NotificationConditionManagerInterface
   */
  protected $conditionManager;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'data_stream_notification',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Get the notification condition manager.
    $this->conditionManager = $this->container->get('plugin.manager.data_stream_notification_condition');
  }

  /**
   * Test the logic of the numeric condition plugin.
   */
  public function testNumericCondition() {

    // Define test cases for each supported condition.
    // Each test case is a value/result pair where the threshold is always 0.
    $conditions = [];
    $conditions['<'] = [
      -5 => TRUE,
      0 => FALSE,
      5 => FALSE,
    ];
    $conditions['>'] = [
      -5 => FALSE,
      0 => FALSE,
      5 => TRUE,
    ];

    // Run each test case.
    foreach ($conditions as $condition => $test_cases) {

      // Create a numeric condition instance with a threshold of 0.
      $configuration = [
        'threshold' => 0,
        'condition' => $condition,
      ];
      /** @var \Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition\NotificationConditionInterface $numeric_condition */
      $numeric_condition = $this->conditionManager->createInstance('numeric', $configuration);

      // Assert each test case.
      foreach ($test_cases as $value => $expected) {
        $numeric_condition->setContextValue('value', $value);
        $this->assertEquals($expected, $numeric_condition->execute(), 'Testing: ' . $value . ' result is ' . (string) $expected);
      }
    }

    // Test when value is non-numeric.
    $numeric_condition->setContextValue('value', 'string');
    $this->assertFalse($numeric_condition->execute());

    // Test when there is no "value" in the context.
    $numeric_condition->setContextValue('value', NULL);
    $this->assertFalse($numeric_condition->execute());
  }

}
