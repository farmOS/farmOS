<?php

namespace Drupal\Tests\data_stream\Kernel;

use Drupal\Tests\token\Kernel\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

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
