<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_api\Kernel;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AnonymousUserSession;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\asset\Entity\AssetInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests farmOS API features.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
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
    'entity',
    'entity_reference_revisions',
    'farm_api',
    'farm_api_test',
    'farm_entity',
    'farm_entity_access',
    'farm_field',
    'farm_log_asset',
    'farm_log_quantity',
    'farm_manager',
    'farm_role',
    'farm_unit',
    'file',
    'fraction',
    'image',
    'jsonapi',
    'log',
    'options',
    'quantity',
    'serialization',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('file');
    $this->installEntitySchema('log');
    $this->installEntitySchema('quantity');
    $this->installConfig([
      'farm_api_test',
      'farm_log_asset',
      'farm_manager',
      'farm_unit',
      'jsonapi',
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

    // Allow JSON:API write operations.
    // This would normally be done by farm_api_install(), which does not run
    // in Kernel tests (it also does other things we don't need).
    \Drupal::configFactory()->getEditable('jsonapi.settings')->set('read_only', FALSE)->save();

    // Set up a user with the farm_manager role.
    $user = $this->setUpCurrentUser([], [], FALSE);
    $user->addRole('farm_manager');
  }

  /**
   * Test common farmOS API requests.
   */
  public function testApi() {

    // Test that the API root path is /api and it contains meta.farm info.
    $data = $this->assertApiRequest('/api');
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
    $data = $this->assertApiRequest('/api/asset/test', 'POST', $payload);
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
    $data = $this->assertApiRequest('/api/log/test', 'POST', $payload);
    $this->assertNotEmpty($data['data']['id']);
    $this->assertEquals($log_type, $data['data']['type']);
    $this->assertEquals($asset_id, $data['data']['relationships']['asset']['data'][0]['id']);

    // Get the log ID.
    $log_id = $data['data']['id'];

    // Test that the asset and log appear in collection endpoints.
    $data = $this->assertApiRequest('/api/asset/test');
    $this->assertCount(1, $data['data']);
    $this->assertEquals($asset_id, $data['data'][0]['id']);
    $data = $this->assertApiRequest('/api/log/test');
    $this->assertCount(1, $data['data']);
    $this->assertEquals($log_id, $data['data'][0]['id']);

    // Test retrieving both asset and log individually by UUID.
    $data = $this->assertApiRequest('/api/asset/test/' . $asset_id);
    $this->assertEquals($asset_id, $data['data']['id']);
    $data = $this->assertApiRequest('/api/log/test/' . $log_id);
    $this->assertEquals($log_id, $data['data']['id']);

    // Test updating assets and logs.
    $payload = [
      'type' => $asset_type,
      'id' => $asset_id,
      'attributes' => [
        'name' => 'Updated asset name',
      ],
    ];
    $data = $this->assertApiRequest('/api/asset/test/' . $asset_id, 'PATCH', $payload);
    $this->assertEquals($asset_id, $data['data']['id']);
    $data = $this->assertApiRequest('/api/asset/test/' . $asset_id);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);
    $payload = [
      'type' => $log_type,
      'id' => $log_id,
      'attributes' => [
        'name' => 'Updated log name',
      ],
    ];
    $data = $this->assertApiRequest('/api/log/test/' . $log_id, 'PATCH', $payload);
    $this->assertEquals($log_id, $data['data']['id']);
    $data = $this->assertApiRequest('/api/log/test/' . $log_id);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);

    // Test deleting logs and assets.
    $this->assertApiRequest('/api/log/test/' . $log_id, 'DELETE');
    $data = $this->assertApiRequest('/api/log/test');
    $this->assertCount(0, $data['data']);
    $data = $this->assertApiRequest('/api/asset/test/' . $asset_id, 'DELETE');
    $data = $this->assertApiRequest('/api/asset/test');
    $this->assertCount(0, $data['data']);
  }

  /**
   * Test allowing resources.
   */
  public function testAllowedApiResources() {

    // Test that core entity type resources are available.
    $this->assertApiRequest('/api/asset/test');
    $this->assertApiRequest('/api/file/file');
    $this->assertApiRequest('/api/log/test');
    $this->assertApiRequest('/api/user/user');
    $this->assertApiRequest('/api/user_role/user_role');

    // Test that view entity type resource is not available.
    $this->assertApiRequest('/api/view/view', 'GET', [], Response::HTTP_NOT_FOUND);

    // Install the farm_api_test_allowed_resources, which allows view entities.
    $this->enableModules(['farm_api_test_allowed_resources']);

    // Test that view entity type resource is now available.
    $this->assertApiRequest('/api/view/view');

    // Test that log entity type resources are now unavailable.
    $this->assertApiRequest('/api/log/test', 'GET', [], Response::HTTP_NOT_FOUND);

  }

  /**
   * Test that entity revisions are created with PATCH requests.
   */
  public function testEntityRevisions() {

    // Test creating an asset.
    $asset_type = 'asset--test';
    $payload = [
      'type' => $asset_type,
      'attributes' => [
        'name' => 'Test asset',
      ],
    ];
    $data = $this->assertApiRequest('/api/asset/test', 'POST', $payload);
    $this->assertNotEmpty($data['data']['id']);
    $this->assertNotEmpty($data['data']['attributes']['drupal_internal__id']);
    $this->assertEquals($asset_type, $data['data']['type']);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);

    // Load the asset.
    $asset_storage = \Drupal::entityTypeManager()->getStorage('asset');
    $asset_id = $data['data']['attributes']['drupal_internal__id'];
    $asset = $asset_storage->load($asset_id);
    $this->assertInstanceOf(AssetInterface::class, $asset);

    // Confirm that there is a single revision.
    $revision_ids = $this->revisionIds($asset);
    $this->assertCount(1, $revision_ids);

    // Update the asset via the API.
    $payload = [
      'type' => $asset_type,
      'id' => $data['data']['id'],
      'attributes' => [
        'name' => 'Test asset update',
      ],
    ];
    $data = $this->assertApiRequest('/api/asset/test/' . $data['data']['id'], 'PATCH', $payload);
    $data = $this->assertApiRequest('/api/asset/test/' . $data['data']['id']);
    $this->assertEquals($payload['attributes']['name'], $data['data']['attributes']['name']);

    // Reload the asset.
    $asset = $asset_storage->load($asset_id);

    // Confirm that there are two revisions.
    $revision_ids = $this->revisionIds($asset);
    $this->assertCount(2, $revision_ids);
  }

  /**
   * Loads all revision IDs of an entity sorted by revision ID descending.
   *
   * This is copied+modified from RevisionControllerTrait::revisionIds().
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   *
   * @return mixed[]
   *   Returns a list of revision IDs.
   */
  protected function revisionIds(EntityInterface $entity) {
    $entity_type = $entity->getEntityType();
    $result = \Drupal::entityTypeManager()->getStorage($entity_type->id())->getQuery()
      ->allRevisions()
      ->condition($entity_type->getKey('id'), $entity->id())
      ->sort($entity_type->getKey('revision'), 'DESC')
      ->accessCheck(TRUE)
      ->execute();
    return array_keys($result);
  }

  /**
   * Test filtered JSON:API requests.
   */
  public function testJsonApiEntityFilterAccess() {

    // Get entity storage.
    $asset_storage = \Drupal::entityTypeManager()->getStorage('asset');
    $log_storage = \Drupal::entityTypeManager()->getStorage('log');
    $quantity_storage = \Drupal::entityTypeManager()->getStorage('quantity');

    // Create test entities.
    $asset = $asset_storage->create([
      'type' => 'test',
      'name' => 'test',
    ]);
    $asset->save();
    $quantity = $quantity_storage->create([
      'type' => 'test',
      'label' => 'test',
    ]);
    $quantity->save();
    $log = $log_storage->create([
      'type' => 'test',
      'name' => 'test',
      'quantity' => [$quantity],
    ]);
    $log->save();

    // Confirm that unfiltered queries work.
    $this->assertApiFilter('asset', 'test', [], 1);
    $this->assertApiFilter('log', 'test', [], 1);
    $this->assertApiFilter('quantity', 'test', [], 1);

    // Confirm that filtered queries work.
    $this->assertApiFilter('asset', 'test', ['name' => 'test'], 1);
    $this->assertApiFilter('log', 'test', ['name' => 'test'], 1);
    $this->assertApiFilter('quantity', 'test', ['label' => 'test'], 1);

    // Log out and confirm that filtered queries return empty results.
    $user = new AnonymousUserSession();
    $this->setCurrentUser($user);
    $this->assertApiFilter('asset', 'test', ['name' => 'test'], 0);
    $this->assertApiFilter('log', 'test', ['name' => 'test'], 0);
    $this->assertApiFilter('quantity', 'test', ['label' => 'test'], 0);
  }

  /**
   * Helper function for testing filtered JSON:API queries.
   *
   * @param string $entity_type
   *   The entity type.
   * @param string $bundle
   *   The bundle.
   * @param array $filters
   *   Array of filter key/values. These will be appended as ?filter= params
   *   and checked for in the returned data.
   * @param int $expected_count
   *   The expected count of results.
   */
  protected function assertApiFilter(string $entity_type, string $bundle, array $filters, int $expected_count) {
    $endpoint = '/api/' . $entity_type . '/' . $bundle;
    if (!empty($filters)) {
      $endpoint .= '?' . http_build_query(['filter' => $filters]);
    }
    $data = $this->assertApiRequest($endpoint);
    $this->assertCount($expected_count, $data['data']);
    foreach ($data['data'] as $item) {
      foreach ($filters as $key => $value) {
        $this->assertEquals($value, $item['attributes'][$key]);
      }
    }
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
   * @param int|null $expected_response
   *   The expected response status code. If null, this will default to a
   *   successful status code based on the request method.
   *
   * @return array
   *   An array of JSON-decoded data returned by the request.
   */
  protected function assertApiRequest(string $endpoint, string $method = 'GET', array $payload = [], int|null $expected_response = NULL) {
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
    if (is_null($expected_response)) {
      $expected_responses = [
        'GET' => Response::HTTP_OK,
        'POST' => Response::HTTP_CREATED,
        'PATCH' => Response::HTTP_OK,
        'DELETE' => Response::HTTP_NO_CONTENT,
      ];
      $expected_response = $expected_responses[$method];
    }
    $this->assertEquals($expected_response, $response->getStatusCode());
    return Json::decode($response->getContent());
  }

}
