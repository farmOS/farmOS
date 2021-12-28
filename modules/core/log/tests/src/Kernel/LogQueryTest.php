<?php

namespace Drupal\Tests\farm_log\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for farmOS log query factory.
 *
 * @group farm
 */
class LogQueryTest extends KernelTestBase {

  /**
   * Log query factory service.
   *
   * @var \Drupal\farm_log\LogQueryFactoryInterface
   */
  protected $logQueryFactory;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'log',
    'farm_field',
    'farm_log',
    'farm_log_asset',
    'farm_log_query_test',
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->logQueryFactory = \Drupal::service('farm.log_query');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_log_query_test',
    ]);
  }

  /**
   * Test log query factory.
   */
  public function testLogQueryFactory() {

    // Get asset and log storage.
    $asset_storage = \Drupal::service('entity_type.manager')->getStorage('asset');
    $log_storage = \Drupal::service('entity_type.manager')->getStorage('log');

    // Create one asset and two logs of different types.
    $asset = $asset_storage->create(['type' => 'test']);
    $asset->save();
    $foo_log = $log_storage->create(['type' => 'foo']);
    $foo_log->save();
    $bar_log = $log_storage->create(['type' => 'bar']);
    $bar_log->save();

    // Test that the logs are in default log query results.
    $log_ids = $this->logQueryFactory->getQuery()->accessCheck(FALSE)->execute();
    $this->assertContains($foo_log->id(), $log_ids, 'Log 1 appears in log query results.');
    $this->assertContains($bar_log->id(), $log_ids, 'Log 2 appears in log query results.');

    // Test that results can be filtered by log type.
    $log_ids = $this->logQueryFactory->getQuery(['type' => 'foo'])->accessCheck(FALSE)->execute();
    $this->assertContains($foo_log->id(), $log_ids, 'Log query results can be filtered by type.');

    // Set the timestamp of one log to the future.
    $now = \Drupal::time()->getRequestTime();
    $foo_log->timestamp = $now + 86400;
    $foo_log->save();

    // Test that results can be filtered by timestamp.
    $log_ids = $this->logQueryFactory->getQuery(['timestamp' => $now])->accessCheck(FALSE)->execute();
    $this->assertNotContains($foo_log->id(), $log_ids, 'Log query results can be filtered by timestamp.');

    // Set the status of one log to complete.
    $bar_log->status = 'complete';
    $bar_log->save();

    // Test that results can be filtered by status.
    $log_ids = $this->logQueryFactory->getQuery(['status' => 'pending'])->accessCheck(FALSE)->execute();
    $this->assertNotContains($bar_log->id(), $log_ids, 'Log query results can be filtered by status.');

    // Reference the asset in one of the logs.
    $foo_log->asset[] = $asset;
    $foo_log->save();

    // Test that results can be filtered by asset reference.
    $log_ids = $this->logQueryFactory->getQuery(['asset' => $asset])->accessCheck(FALSE)->execute();
    $this->assertContains($foo_log->id(), $log_ids, 'Log that references asset is included in results.');
    $this->assertNotContains($bar_log->id(), $log_ids, 'Log that does not reference asset is not included in results.');

    // Set the timestamps of both logs to now.
    $now = \Drupal::time()->getRequestTime();
    $foo_log->timestamp = $now;
    $foo_log->save();
    $bar_log->timestamp = $now;
    $bar_log->save();

    // Test that logs with the same timestamp are sorted by ID descending.
    $log_ids = $this->logQueryFactory->getQuery()->accessCheck(FALSE)->execute();
    $this->assertEquals($bar_log->id(), reset($log_ids), 'Logs with the same timestamp are sorted by ID descending.');

    // Set the timestamp of one log to the future.
    $now = \Drupal::time()->getRequestTime();
    $foo_log->timestamp = $now + 86400;
    $foo_log->save();

    // Test that logs are sorted by timestamp descending.
    $log_ids = $this->logQueryFactory->getQuery()->accessCheck(FALSE)->execute();
    $this->assertEquals($foo_log->id(), reset($log_ids), 'Logs are sorted by timestamp descending.');

    // Test that results can be limited.
    $log_ids = $this->logQueryFactory->getQuery(['limit' => 1])->accessCheck(FALSE)->execute();
    $this->assertEquals(1, count($log_ids), 'Log query results can be limited.');
  }

}
