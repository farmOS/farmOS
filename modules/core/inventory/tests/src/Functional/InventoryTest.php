<?php

namespace Drupal\Tests\farm_inventory\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;

/**
 * Tests for farmOS inventory logic.
 *
 * @group farm
 */
class InventoryTest extends FarmBrowserTestBase {

  use JsonApiRequestTestTrait;

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_inventory',
    'farm_inventory_test',
    'farm_unit',
    'farm_api',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with permission to administer assets.
    $this->user = $this->createUser(['administer assets']);
    $this->drupalLogin($this->user);
  }

  /**
   * Test retrieving asset inventory via API.
   */
  public function testApiInventory() {

    // Load asset, log, quantity, and term storage.
    $entity_type_manager = $this->container->get('entity_type.manager');
    $asset_storage = $entity_type_manager->getStorage('asset');
    $log_storage = $entity_type_manager->getStorage('log');
    $quantity_storage = $entity_type_manager->getStorage('quantity');
    $term_storage = $entity_type_manager->getStorage('taxonomy_term');

    // Create a new asset.
    $asset = $asset_storage->create([
      'type' => 'container',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Request the asset JSON via API.
    $response = $this->requestApiEntity($asset);

    // Test that the inventory field is included in the response.
    $this->assertArrayHasKey('inventory', $response['data']['attributes']);

    // Confirm that the asset does not have any inventory in API.
    $this->assertEmpty($response['data']['attributes']['inventory']);

    // Create a "liters" unit term.
    $term = $term_storage->create([
      'name' => 'liters',
      'vid' => 'unit',
    ]);
    $term->save();

    // Create an inventory adjustment log+quantity that resets the volume
    // (liters) inventory of the asset.
    $quantity = $quantity_storage->create([
      'type' => 'test',
      'measure' => 'volume',
      'value' => [
        'numerator' => '2',
        'denominator' => '1',
      ],
      'units' => ['target_id' => $term->id()],
      'inventory_adjustment' => 'reset',
      'inventory_asset' => ['target_id' => $asset->id()],
    ]);
    $quantity->save();
    $log = $log_storage->create([
      'type' => 'adjustment',
      'status' => 'done',
      'quantity' => [
        'target_id' => $quantity->id(),
        'target_revision_id' => $quantity->getRevisionId(),
      ],
    ]);
    $log->save();

    // Confirm that the asset's inventory was updated in the API.
    $response = $this->requestApiEntity($asset);
    $this->assertNotEmpty($response['data']['attributes']['inventory']);
    $this->assertEquals('volume', $response['data']['attributes']['inventory'][0]['measure']);
    $this->assertEquals('2', $response['data']['attributes']['inventory'][0]['value']);
    $this->assertEquals('liters', $response['data']['attributes']['inventory'][0]['units']);

    // Delete the log.
    $log->delete();

    // Confirm that the asset's inventory was updated in the API.
    $response = $this->requestApiEntity($asset);
    $this->assertEmpty($response['data']['attributes']['inventory']);
  }

  /**
   * Helper function to request an entity from the API.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to request.
   *
   * @return array
   *   The json-decoded response.
   */
  protected function requestApiEntity(EntityInterface $entity) {
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $asset_uri = "base://api/{$entity->getEntityType()->id()}/{$entity->bundle()}/{$entity->uuid()}";
    $response = $this->request('GET', Url::fromUri($asset_uri), $request_options);
    return Json::decode((string) $response->getBody());
  }

}
