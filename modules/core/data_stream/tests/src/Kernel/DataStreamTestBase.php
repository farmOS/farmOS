<?php

namespace Drupal\Tests\data_stream\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests for Data Streams.
 *
 * @group farm
 */
abstract class DataStreamTestBase extends KernelTestBase {

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
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('data_stream');
    $this->installConfig(['data_stream']);
    $this->installSchema('data_stream', 'data_stream_basic');
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
