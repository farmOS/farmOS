<?php

namespace Drupal\Tests\farm_map\Functional;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\field\Functional\FieldTestBase;

/**
 * Tests the farmOS Geofield widget.
 *
 * @group farm
 */
class GeofieldWidgetTest extends FieldTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_map',
    'geofield',
    'entity_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A field storage with cardinality 1 to use in this test class.
   *
   * @var \Drupal\field\Entity\FieldStorageConfig
   */
  protected $fieldStorage;

  /**
   * A Field to use in this test class.
   *
   * @var \Drupal\field\Entity\FieldConfig
   */
  protected $field;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->fieldStorage = FieldStorageConfig::create([
      'field_name' => 'geofield_field',
      'entity_type' => 'entity_test',
      'type' => 'geofield',
      'settings' => [
        'backend' => 'geofield_backend_default',
      ],
    ]);
    $this->fieldStorage->save();

    $this->field = FieldConfig::create([
      'field_storage' => $this->fieldStorage,
      'bundle' => 'entity_test',
      'settings' => [
        'backend' => 'geofield_backend_default',
      ],
    ]);
    $this->field->save();

    // Create a web user.
    $this->drupalLogin($this->drupalCreateUser(['view test entity', 'administer entity_test content']));
  }

  /**
   * Test the farmOS Geofield widget.
   */
  public function testGeofieldWidget() {
    EntityFormDisplay::load('entity_test.entity_test.default')
      ->setComponent($this->fieldStorage->getName(), [
        'type' => 'farm_map_geofield',
      ])
      ->save();

    // Create an entity.
    $entity = EntityTest::create([
      'user_id' => 1,
      'name' => $this->randomMachineName(),
    ]);
    $entity->save();

    // With no field data, no buttons are checked.
    $this->drupalGet('entity_test/manage/' . $entity->id() . '/edit');
    $this->assertSession()->pageTextContains('geofield_field');

    // Test a valid WKT value.
    $edit = [
      'name[0][value]' => 'Arnedo',
      'geofield_field[0][value]' => 'POLYGON ((30 10, 40 40, 20 40, 10 20, 30 10))',
    ];
    $this->submitForm($edit, 'Save');
    $this->assertFieldValues($entity, 'geofield_field', ['POLYGON ((30 10, 40 40, 20 40, 10 20, 30 10))']);

    // Test a valid GeoJSON value.
    $edit = [
      'name[0][value]' => 'Dinagat Islands',
      'geofield_field[0][value]' => '{
  "type": "Feature",
  "geometry": {
    "type": "Point",
    "coordinates": [125.6, 10.1]
  },
  "properties": {
    "name": "Dinagat Islands"
  }
}',
    ];
    $this->submitForm($edit, 'Save');
    $this->assertFieldValues($entity, 'geofield_field', ['POINT (125.6 10.1)']);

    // Test a valid WKB value.
    $edit = [
      'name[0][value]' => 'Arnedo',
      'geofield_field[0][value]' => '0101000020E6100000705F07CE19D100C0865AD3BCE31C4540',
    ];
    $this->submitForm($edit, 'Save');
    $this->assertFieldValues($entity, 'geofield_field', ['POINT (-2.1021 42.2257)']);
  }

}
