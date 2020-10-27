<?php

namespace Drupal\Tests\data_stream\Kernel;

use Drupal\Tests\token\Kernel\KernelTestBase;

/**
 * Tests for Data Streams.
 *
 * @group farm
 */
abstract class DataStreamTestBase extends KernelTestBase {

  /**
   * Data stream API path.
   *
   * @var string
   */
  protected $streamApiPath = '/data_stream';

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
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('data_stream');
    $this->installConfig(['data_stream']);
    $this->installSchema('data_stream', 'data_stream_data_storage');
  }

}
