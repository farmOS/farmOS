<?php

namespace Drupal\Tests\farm_api\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests farmOS API features.
 *
 * @group farm
 */
class FarmApiTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'consumers',
    'entity',
    'farm_api',
    'farm_api_test',
    'farm_entity',
    'farm_entity_views',
    'farm_field',
    'farm_log_asset',
    'farm_role',
    'farm_role_roles',
    'file',
    'image',
    'jsonapi',
    'jsonapi_extras',
    'log',
    'options',
    'serialization',
    'simple_oauth',
    'state_machine',
    'system',
    'user',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installConfig([
      'farm_api_test',
      'farm_entity_views',
      'farm_role_roles',
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

    // Set up a user with the farm_manager role.
    $user = $this->setUpCurrentUser([], [], FALSE);
    $user->addRole('farm_manager');
  }

  /**
   * Test common farmOS API requests.
   */
  public function testApi() {

    // Test that the API root path is /api and it contains meta.farm info.
    $data = $this->apiRequest('/api');
    $this->assertNotEmpty($data['meta']['farm']);
    $this->assertEquals('API Test', $data['meta']['farm']['name']);

    // Test creating an asset.
    $asset_type = 'asset--test';
    $payload = [
      'type' => $asset_type,
      'attributes' => [
        'name' => 'Test asset',
      ],
    ];
    $data = $this->apiRequest('/api/asset/test', 'POST', $payload);
    $this->assertNotEmpty($data['data']['id']);
    $this->assertEquals($asset_type, $data['data']['type']);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);

    // Get the asset ID.
    $asset_id = $data['data']['id'];

    // Test creating a log that references the asset.
    $log_type = 'log--test';
    $payload = [
      'type' => $log_type,
      'relationships' => [
        'asset' => [
          'data' => [
            [
              'id' => $asset_id,
              'type' => $asset_type,
            ],
          ],
        ],
      ],
    ];
    $data = $this->apiRequest('/api/log/test', 'POST', $payload);
    $this->assertNotEmpty($data['data']['id']);
    $this->assertEquals($log_type, $data['data']['type']);
    $this->assertEquals($asset_id, $data['data']['relationships']['asset']['data'][0]['id']);

    // Get the log ID.
    $log_id = $data['data']['id'];

    // Test that the asset and log appear in collection endpoints.
    $data = $this->apiRequest('/api/asset/test');
    $this->assertCount(1, $data['data']);
    $this->assertEquals($asset_id, $data['data'][0]['id']);
    $data = $this->apiRequest('/api/log/test');
    $this->assertCount(1, $data['data']);
    $this->assertEquals($log_id, $data['data'][0]['id']);

    // Test retrieving both asset and log individually by UUID.
    $data = $this->apiRequest('/api/asset/test/' . $asset_id);
    $this->assertEquals($asset_id, $data['data']['id']);
    $data = $this->apiRequest('/api/log/test/' . $log_id);
    $this->assertEquals($log_id, $data['data']['id']);

    // Test updating assets and logs.
    $payload = [
      'type' => $asset_type,
      'id' => $asset_id,
      'attributes' => [
        'name' => 'Updated asset name',
      ],
    ];
    $data = $this->apiRequest('/api/asset/test/' . $asset_id, 'PATCH', $payload);
    $this->assertEquals($asset_id, $data['data']['id']);
    $data = $this->apiRequest('/api/asset/test/' . $asset_id);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);
    $payload = [
      'type' => $log_type,
      'id' => $log_id,
      'attributes' => [
        'name' => 'Updated log name',
      ],
    ];
    $data = $this->apiRequest('/api/log/test/' . $log_id, 'PATCH', $payload);
    $this->assertEquals($log_id, $data['data']['id']);
    $data = $this->apiRequest('/api/log/test/' . $log_id);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);

    // Test deleting logs and assets.
    $this->apiRequest('/api/log/test/' . $log_id, 'DELETE');
    $data = $this->apiRequest('/api/log/test');
    $this->assertCount(0, $data['data']);
    $data = $this->apiRequest('/api/asset/test/' . $asset_id, 'DELETE');
    $data = $this->apiRequest('/api/asset/test');
    $this->assertCount(0, $data['data']);
  }

  /**
   * Helper function for performing an API request.
   *
   * @param string $endpoint
   *   The API endpoint.
   * @param string $method
   *   The request method (eg: GET, POST, PATCH, DELETE).
   * @param array $payload
   *   Array of data to send as a payload.
   *
   * @return array
   *   An array of JSON-decoded data returned by the request.
   */
  protected function apiRequest(string $endpoint, string $method = 'GET', array $payload = []) {
    $http_kernel = $this->container->get('http_kernel');
    $content = '';
    if (!empty($payload)) {
      $content = Json::encode([
        'data' => $payload,
      ]);
    }
    $request = Request::create($endpoint, $method, [], [], [], [], $content);
    $request->headers->set('Accept', 'application/vnd.api+json');
    $request->headers->set('Content-Type', 'application/vnd.api+json');
    $response = $http_kernel->handle($request);
    $expected_response = [
      'GET' => Response::HTTP_OK,
      'POST' => Response::HTTP_CREATED,
      'PATCH' => Response::HTTP_OK,
      'DELETE' => Response::HTTP_NO_CONTENT,
    ];
    $this->assertEquals($expected_response[$method], $response->getStatusCode());
    return Json::decode($response->getContent());
  }

}
