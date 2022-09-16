<?php

namespace Drupal\Tests\farm_api\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;

/**
 * Tests the API entry point functionality.
 *
 * @group farm
 */
class EntryPointTest extends FarmBrowserTestBase {

  use JsonApiRequestTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'basic_auth',
    'node',
    'farm_api',
    'views',
  ];

  /**
   * Test GET to the entry point.
   *
   * We extend the core JSONAPI EntryPoint controller to include a 'meta' key
   * in the root controller. We need to test that we aren't changing anything
   * else.
   *
   * This test is a copy of the core JSONAPI testEntryPoint test with the
   * following modifications:
   *  - The base path is /api, not /jsonapi
   *  - The root document MUST have a 'meta' key.
   *
   * @see \Drupal\Tests\jsonapi\Functional\EntryPointTest
   */
  public function testEntryPoint() {
    $request_options = [];
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $response = $this->request('GET', Url::fromUri('base://api'), $request_options);
    $document = Json::decode((string) $response->getBody());
    $expected_cache_contexts = [
      'url.site',
      'user.roles:authenticated',
    ];
    $this->assertTrue($response->hasHeader('X-Drupal-Cache-Contexts'));
    $optimized_expected_cache_contexts = \Drupal::service('cache_contexts_manager')->optimizeTokens($expected_cache_contexts);
    $this->assertSame($optimized_expected_cache_contexts, explode(' ', $response->getHeader('X-Drupal-Cache-Contexts')[0]));
    $links = $document['links'];
    $this->assertMatchesRegularExpression('/.*\/api/', $links['self']['href']);
    $this->assertMatchesRegularExpression('/.*\/api\/user\/user/', $links['user--user']['href']);
    $this->assertMatchesRegularExpression('/.*\/api\/node_type\/node_type/', $links['node_type--node_type']['href']);

    // farm_api alters the root document to include a 'meta' key.
    $this->assertArrayHasKey('meta', $document);

    // A `me` link must be present for authenticated users.
    $user = $this->createUser();
    $request_options[RequestOptions::HEADERS]['Authorization'] = 'Basic ' . base64_encode($user->name->value . ':' . $user->passRaw);
    $response = $this->request('GET', Url::fromUri('base://api'), $request_options);
    $document = Json::decode((string) $response->getBody());
    $this->assertArrayHasKey('meta', $document);
    $this->assertStringEndsWith('/api/user/user/' . $user->uuid(), $document['meta']['links']['me']['href']);
  }

  /**
   * Test that the meta.farm data is correct.
   */
  public function testFarmMeta() {
    $request_options = [];
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $response = $this->request('GET', Url::fromUri('base://api'), $request_options);
    $document = Json::decode((string) $response->getBody());

    // Assert that the meta key exists.
    $this->assertArrayHasKey('meta', $document);

    // Assert that the meta.farm key exists.
    $this->assertArrayHasKey('farm', $document['meta']);

    // Get the farm profile info.
    $farm_info = \Drupal::service('extension.list.profile')->getExtensionInfo('farm');

    // Array of expected values.
    $expected_values = [
      'name' => $this->config('system.site')->get('name'),
      'url' => $this->baseUrl,
      'version' => $farm_info['version'],
    ];
    foreach ($expected_values as $key => $value) {
      $this->assertArrayHasKey($key, $document['meta']['farm']);
      $this->assertEquals($value, $document['meta']['farm'][$key]);
    }
  }

}
