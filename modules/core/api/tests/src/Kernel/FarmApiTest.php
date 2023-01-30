<?php

namespace Drupal\Tests\farm_api\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests farmOS API features.
 *
 * @group farm
 */
class FarmApiTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_api',
    'jsonapi',
    'jsonapi_extras',
    'serialization',
    'simple_oauth',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig([
      'jsonapi',
      'jsonapi_extras',
      'system',
    ]);

    // Set the install profile.
    // This is necessary because farm_api's FarmEntryPoint needs to load the
    // farm profile information from Drupal's extension list service. During
    // kernel tests the installProfile property of the ExtensionList class does
    // not get set automatically.
    $this->setInstallProfile('farm');

    // Set the site name so that we can check for it in /api meta.farm info.
    \Drupal::configFactory()->getEditable('system.site')->set('name', 'API Test')->save();

    // Allow JSON:API write operations and change the base path to /api.
    // These would normally be done by farm_api_install(), which does not run
    // in Kernel tests (it also does other things we don't need).
    \Drupal::configFactory()->getEditable('jsonapi.settings')->set('read_only', FALSE)->save();
    \Drupal::configFactory()->getEditable('jsonapi_extras.settings')->set('path_prefix', 'api')->save();
  }

  /**
   * Test common farmOS API requests.
   */
  public function testApi() {

    // Test that the API root path is /api and it contains meta.farm info.
    $data = $this->apiRequest('/api');
    $this->assertNotEmpty($data['meta']['farm']);
    $this->assertEquals('API Test', $data['meta']['farm']['name']);
  }

  /**
   * Helper function for performing an API request.
   *
   * @param string $endpoint
   *   The API endpoint.
   * @param string $method
   *   The request method (eg: GET, POST, PATCH, DELETE).
   *
   * @return array
   *   An array of JSON-decoded data returned by the request.
   */
  protected function apiRequest(string $endpoint, string $method = 'GET') {
    $http_kernel = $this->container->get('http_kernel');
    $request = Request::create($endpoint, $method);
    $request->headers->set('Accept', 'application/vnd.api+json');
    $response = $http_kernel->handle($request);
    $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    return Json::decode($response->getContent());
  }

}
