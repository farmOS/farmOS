<?php

namespace Drupal\Tests\farm_birth\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\taxonomy\Entity\Term;

/**
 * Tests for farmOS birth log logic.
 *
 * @group farm
 */
class BirthTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'log',
    'entity',
    'farm_animal',
    'farm_animal_type',
    'farm_birth',
    'farm_entity',
    'farm_field',
    'farm_id_tag',
    'farm_log',
    'file',
    'geofield',
    'image',
    'options',
    'state_machine',
    'user',
    'taxonomy',
    'text',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_animal',
      'farm_animal_type',
      'farm_birth',
    ]);
  }

  /**
   * Test that saving a birth log syncs data to children assets.
   */
  public function testBirthLogChildrenSync() {

    // Create a Cow animal type term.
    /** @var \Drupal\taxonomy\TermInterface $cow */
    $cow = Term::create([
      'name' => 'Cow',
      'vid' => 'animal_type',
    ]);
    $cow->save();

    // Create a mother animal asset.
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $mother = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $mother->save();

    // Create 2 children animal assets.
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $child1 = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $child1->save();
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $child2 = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $child2->save();

    // Confirm that the children do not have parents or dates of birth.
    $this->assertEmpty($child1->get('parent')->referencedEntities());
    $this->assertEmpty($child2->get('parent')->referencedEntities());
    $this->assertEmpty($child1->get('birthdate')->value);
    $this->assertEmpty($child2->get('birthdate')->value);

    // Get the current timestamp.
    $timestamp = \Drupal::time()->getRequestTime();

    // Create a birth log that references the mother and children.
    $log = Log::create([
      'type' => 'birth',
      'timestamp' => $timestamp,
      'mother' => ['target_id' => $mother->id()],
      'asset' => [
        ['target_id' => $child1->id()],
        ['target_id' => $child2->id()],
      ],
    ]);
    $log->save();

    // Reload children assets.
    $child1 = Asset::load($child1->id());
    $child2 = Asset::load($child2->id());

    // Confirm that the children list the mother as their parent and have dates
    // of birth equal to the birth log.
    $this->assertNotEmpty($child1->get('parent')->referencedEntities());
    $this->assertNotEmpty($child2->get('parent')->referencedEntities());
    $this->assertEquals($mother->id(), $child1->get('parent')->referencedEntities()[0]->id());
    $this->assertEquals($mother->id(), $child2->get('parent')->referencedEntities()[0]->id());
    $this->assertEquals($timestamp, $child1->get('birthdate')->value);
    $this->assertEquals($timestamp, $child2->get('birthdate')->value);
  }

}
