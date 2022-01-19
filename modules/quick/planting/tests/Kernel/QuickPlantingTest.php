<?php

namespace Drupal\Tests\farm_quick_planting\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Form\FormState;
use Drupal\KernelTests\KernelTestBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests for farmOS planting quick form.
 *
 * @group farm
 */
class QuickPlantingTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * Asset entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetStorage;

  /**
   * Log entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $logStorage;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity',
    'entity_reference_revisions',
    'farm_entity',
    'farm_entity_fields',
    'farm_field',
    'farm_format',
    'farm_harvest',
    'farm_land',
    'farm_location',
    'farm_log',
    'farm_log_asset',
    'farm_log_quantity',
    'farm_map',
    'farm_plant',
    'farm_plant_type',
    'farm_quantity_standard',
    'farm_quick',
    'farm_quick_planting',
    'farm_season',
    'farm_seeding',
    'farm_transplanting',
    'farm_unit',
    'file',
    'filter',
    'fraction',
    'geofield',
    'image',
    'log',
    'options',
    'quantity',
    'rest',
    'serialization',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
    'views',
    'views_geojson',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setUpCurrentUser([], [], TRUE);
    $this->assetStorage = \Drupal::entityTypeManager()->getStorage('asset');
    $this->logStorage = \Drupal::entityTypeManager()->getStorage('log');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('quantity');
    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installConfig([
      'farm_format',
      'farm_harvest',
      'farm_land',
      'farm_location',
      'farm_plant',
      'farm_quantity_standard',
      'farm_seeding',
      'farm_transplanting',
    ]);
  }

  /**
   * Test simple planting quick form submission.
   */
  public function testQuickPlantingSimple() {

    // Create a season and crop to reference.
    $season = Term::create([
      'name' => '2022',
      'vid' => 'season',
    ]);
    $season->save();
    $crop = Term::create([
      'name' => 'Rice',
      'vid' => 'plant_type',
    ]);
    $crop->save();

    // Submit the planting quick form.
    $this->submitQuickForm([
      'seasons' => [['target_id' => $season->id()]],
      'crops' => [[['target_id' => $crop->id()]]],
      'crop_count' => 1,
      'log_types' => [],
    ]);

    // Confirm that one asset was created.
    $assets = $this->assetStorage->loadMultiple();
    $this->assertCount(1, $assets);

    // Check that the asset's fields were populated correctly.
    $asset = $assets[1];
    $this->assertEquals('plant', $asset->bundle());
    $this->assertEquals('2022 Rice', $asset->label());
    $this->assertEquals('active', $asset->get('status')->value);
    $this->assertEquals($season->id(), $asset->get('season')->referencedEntities()[0]->id());
    $this->assertEquals($crop->id(), $asset->get('plant_type')->referencedEntities()[0]->id());

    // Test with multiple crops.
    $crop1 = Term::create([
      'name' => 'Winter rye',
      'vid' => 'plant_type',
    ]);
    $crop1->save();
    $crop2 = Term::create([
      'name' => 'Vetch',
      'vid' => 'plant_type',
    ]);
    $crop2->save();

    // Submit the planting quick form.
    $this->submitQuickForm([
      'seasons' => [['target_id' => $season->id()]],
      'crops' => [
        [['target_id' => $crop1->id()]],
        [['target_id' => $crop2->id()]],
      ],
      'crop_count' => 2,
      'log_types' => [],
    ]);

    // Confirm that a second asset was created.
    $assets = $this->assetStorage->loadMultiple();
    $this->assertCount(2, $assets);

    // Check that the asset has multiple crops and is named correctly.
    $asset = $assets[2];
    $this->assertEquals('2022 Winter rye, Vetch', $asset->label());
    $this->assertEquals($crop1->id(), $asset->get('plant_type')->referencedEntities()[0]->id());
    $this->assertEquals($crop2->id(), $asset->get('plant_type')->referencedEntities()[1]->id());

    // Test overriding the plant name.
    $custom_name = 'Rice of the 2022 season';
    $this->submitQuickForm([
      'seasons' => [['target_id' => $season->id()]],
      'crops' => [
        [['target_id' => $crop->id()]],
      ],
      'crop_count' => 1,
      'log_types' => [],
      'custom_name' => TRUE,
      'name' => $custom_name,
    ]);

    // Confirm that a third asset was created.
    $assets = $this->assetStorage->loadMultiple();
    $this->assertCount(3, $assets);

    // Check that the asset name was overridden.
    $asset = $assets[3];
    $this->assertEquals($custom_name, $asset->label());
  }

  /**
   * Test planting with logs.
   */
  public function testQuickPlantingLogs() {

    // Create a season, crop, and two land assets to reference.
    $season = Term::create([
      'name' => '2022',
      'vid' => 'season',
    ]);
    $season->save();
    $crop = Term::create([
      'name' => 'Lettuce',
      'vid' => 'plant_type',
    ]);
    $crop->save();
    $land1 = Asset::create([
      'name' => 'Field A',
      'type' => 'land',
      'land_type' => 'field',
      'is_fixed' => TRUE,
      'is_location' => TRUE,
      'status' => 'active',
    ]);
    $land1->save();
    $land2 = Asset::create([
      'name' => 'Field B',
      'type' => 'land',
      'land_type' => 'field',
      'is_fixed' => TRUE,
      'is_location' => TRUE,
      'status' => 'active',
    ]);
    $land2->save();

    // Programmatically submit the planting quick form.
    $this->submitQuickForm([
      'seasons' => [['target_id' => $season->id()]],
      'crops' => [[['target_id' => $crop->id()]]],
      'crop_count' => 1,
      'log_types' => [
        'seeding' => 'seeding',
      ],
      'seeding' => [
        'type' => 'seeding',
        'date' => '2022-05-15',
        'location' => $land1->label(),
        'quantity' => [
          'measure' => 'weight',
          'value' => '10.01',
          'units' => 'kg',
        ],
        'notes' => [
          'value' => 'Lorem ipsum',
          'format' => 'default',
        ],
        'done' => TRUE,
      ],
    ]);

    // Load assets and logs.
    $assets = $this->assetStorage->loadMultiple();
    $logs = $this->logStorage->loadMultiple();

    // Confirm that three assets (land + plant) and one log exists.
    $this->assertCount(3, $assets);
    $this->assertCount(1, $logs);

    // Check that the asset name includes the seeding location.
    $asset = $assets[3];
    $this->assertEquals('2022 Field A Lettuce', $asset->label());

    // Check that the seeding log's fields were populated correctly.
    $log = $logs[1];
    $this->assertEquals('seeding', $log->bundle());
    $this->assertEquals('Seed ' . $asset->label(), $log->label());
    $this->assertEquals(strtotime('2022-05-15'), $log->get('timestamp')->value);
    $this->assertEquals($asset->id(), $log->get('asset')->referencedEntities()[0]->id());
    $this->assertEquals($land1->id(), $log->get('location')->referencedEntities()[0]->id());
    $this->assertEquals('weight', $log->get('quantity')->referencedEntities()[0]->get('measure')->value);
    $this->assertEquals('10.01', $log->get('quantity')->referencedEntities()[0]->get('value')[0]->get('decimal')->getValue());
    $this->assertEquals('kg', $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->get('name')->value);
    $this->assertEquals('Lorem ipsum', $log->get('notes')->value);
    $this->assertEquals('done', $log->get('status')->value);

    // Test creating multiple logs.
    $this->submitQuickForm([
      'seasons' => [['target_id' => $season->id()]],
      'crops' => [[['target_id' => $crop->id()]]],
      'crop_count' => 1,
      'log_types' => [
        'seeding' => 'seeding',
        'transplanting' => 'transplanting',
        'harvest' => 'harvest',
      ],
      'seeding' => [
        'type' => 'seeding',
        'date' => '2022-05-15',
        'location' => $land1->label(),
        'notes' => [],
        'done' => TRUE,
      ],
      'transplanting' => [
        'type' => 'transplanting',
        'date' => '2022-06-15',
        'location' => $land2->label(),
        'notes' => [],
        'done' => FALSE,
      ],
      'harvest' => [
        'type' => 'harvest',
        'date' => '2022-07-15',
        'notes' => [],
        'done' => FALSE,
      ],
    ]);

    // Confirm that another asset and 3 more logs were created.
    $assets = $this->assetStorage->loadMultiple();
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(4, $assets);
    $this->assertCount(4, $logs);

    // Check that the asset name includes the transplanting location.
    $asset = $assets[4];
    $this->assertEquals('2022 Field B Lettuce', $asset->label());

    // Check that the transplanting and harvest logs are pending.
    $log = $logs[3];
    $this->assertEquals('pending', $log->get('status')->value);
    $log = $logs[4];
    $this->assertEquals('pending', $log->get('status')->value);
  }

  /**
   * Helper function for performing a planting quick form submission.
   *
   * @param array $values
   *   The values to submit.
   */
  protected function submitQuickForm(array $values = []) {
    $form_arg = '\Drupal\farm_quick\Form\QuickForm';
    $form_state = (new FormState())->setValues($values);
    \Drupal::formBuilder()->submitForm($form_arg, $form_state, 'planting');
  }

}
