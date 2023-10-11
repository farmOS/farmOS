<?php

namespace Drupal\Tests\farm_birth\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Component\Render\FormattableMarkup;
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
    'farm_entity_fields',
    'farm_entity_views',
    'farm_field',
    'farm_id_tag',
    'farm_log_asset',
    'farm_parent',
    'file',
    'geofield',
    'image',
    'options',
    'state_machine',
    'system',
    'user',
    'taxonomy',
    'text',
    'views',
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
      'farm_entity_views',
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
    /** @var \Drupal\asset\Entity\AssetInterface $mother */
    $mother = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $mother->save();

    // Create 2 children animal assets.
    /** @var \Drupal\asset\Entity\AssetInterface $child1 */
    $child1 = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $child1->save();
    /** @var \Drupal\asset\Entity\AssetInterface $child2 */
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
      'name' => $this->randomMachineName(),
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

    // Create another mother animal asset.
    /** @var \Drupal\asset\Entity\AssetInterface $mother2 */
    $mother2 = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $mother2->save();

    // Create a birth log that references the second mother and one of the
    // original children.
    $log = Log::create([
      'name' => $this->randomMachineName(),
      'type' => 'birth',
      'timestamp' => $timestamp,
      'mother' => ['target_id' => $mother2->id()],
      'asset' => [
        ['target_id' => $child1->id()],
      ],
    ]);
    $log->save();

    // Reload the first child asset.
    $child1 = Asset::load($child1->id());

    // Confirm that the child's parent has NOT changed to the new mother. If a
    // child already has parents they should not be changed.
    $this->assertNotEmpty($child1->get('parent')->referencedEntities());
    $this->assertNotEquals($mother2->id(), $child1->get('parent')->referencedEntities()[0]->id());
  }

  /**
   * Test that only one birth log can reference an asset.
   */
  public function testUniqueBirthLogConstraint() {

    // Create a Cow animal type term.
    /** @var \Drupal\taxonomy\TermInterface $cow */
    $cow = Term::create([
      'name' => 'Cow',
      'vid' => 'animal_type',
    ]);
    $cow->save();

    // Create an asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'name' => $this->randomMachineName(),
      'type' => 'animal',
      'animal_type' => ['tid' => $cow->id()],
      'status' => 'active',
    ]);
    $asset->save();

    // Create a birth log that references the asset.
    $log1 = Log::create([
      'name' => $this->randomMachineName(),
      'type' => 'birth',
      'timestamp' => \Drupal::time()->getRequestTime(),
      'asset' => [['target_id' => $asset->id()]],
    ]);

    // Confirm that there are no validation errors.
    $errors = $log1->validate();
    $this->assertCount(0, $errors);

    $log1->save();

    // Create a second birth log that references the asset.
    $log2 = Log::create([
      'name' => $this->randomMachineName(),
      'type' => 'birth',
      'timestamp' => \Drupal::time()->getRequestTime(),
      'asset' => [['target_id' => $asset->id()]],
    ]);

    // Confirm that validation fails.
    $errors = $log2->validate();
    $this->assertCount(1, $errors);
    $this->assertEquals(new FormattableMarkup('%child already has a birth log. More than one birth log cannot reference the same child.', ['%child' => $asset->label()]), $errors[0]->getMessage());
    $this->assertEquals('asset.0.target_id', $errors[0]->getPropertyPath());

    // Try updating the original birth log.
    $log1->set('name', $this->randomMachineName());

    // Confirm there are no validation errors.
    $errors = $log1->validate();
    $this->assertCount(0, $errors);
    $log1->save();
  }

}
