<?php

namespace Drupal\Tests\farm_quick_birth\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\log\Entity\Log;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\farm_quick\Kernel\QuickFormTestBase;

/**
 * Tests for farmOS birth quick form.
 *
 * @group farm
 */
class QuickBirthTest extends QuickFormTestBase {

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId = 'birth';

  /**
   * Asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_animal',
    'farm_animal_type',
    'farm_birth',
    'farm_group',
    'farm_id_tag',
    'farm_land',
    'farm_observation',
    'farm_parent',
    'farm_quantity_standard',
    'farm_quick_birth',
    'farm_unit',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->assetLocation = \Drupal::service('asset.location');
    $this->groupMembership = \Drupal::service('group.membership');
    $this->installConfig([
      'farm_animal',
      'farm_animal_type',
      'farm_birth',
      'farm_group',
      'farm_id_tag',
      'farm_land',
      'farm_observation',
      'farm_quantity_standard',
    ]);
  }

  /**
   * Test birth quick form submission.
   */
  public function testQuickBirth() {

    // Get today's date.
    $today = new DrupalDateTime('midnight');

    // Create two animal breeds.
    $breed1 = Term::create([
      'name' => 'Breed 1',
      'vid' => 'animal_type',
    ]);
    $breed1->save();
    $breed2 = Term::create([
      'name' => 'Breed 2',
      'vid' => 'animal_type',
    ]);
    $breed2->save();

    // Create birth mother, genetic mother, and genetic father animal assets.
    $birth_mother = Asset::create([
      'name' => 'Birth Mother',
      'type' => 'animal',
      'animal_type' => $breed1,
      'sex' => 'F',
      'status' => 'active',
    ]);
    $birth_mother->save();
    $genetic_mother = Asset::create([
      'name' => 'Genetic Mother',
      'type' => 'animal',
      'animal_type' => $breed2,
      'sex' => 'F',
      'status' => 'active',
    ]);
    $genetic_mother->save();
    $genetic_father = Asset::create([
      'name' => 'Genetic Father',
      'type' => 'animal',
      'animal_type' => $breed1,
      'sex' => 'M',
      'status' => 'active',
    ]);
    $genetic_father->save();

    // Create a location asset and move the birth mother there via a log with
    // a timestamp of yesterday.
    $location = Asset::create([
      'name' => 'Field A',
      'type' => 'land',
      'land_type' => 'field',
      'is_fixed' => TRUE,
      'is_location' => TRUE,
      'status' => 'active',
    ]);
    $location->save();
    $movement = Log::create([
      'type' => 'observation',
      'timestamp' => $today->getTimestamp() - 86400,
      'asset' => [$birth_mother],
      'location' => [$location],
      'is_movement' => TRUE,
      'status' => 'done',
    ]);
    $movement->save();

    // Create a group asset.
    $group = Asset::create([
      'name' => 'Herd 1',
      'type' => 'group',
      'status' => 'active',
    ]);
    $group->save();

    // Submit the birth quick form.
    $this->submitQuickForm([
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'child_count' => 2,
      'children' => [
        [
          'name' => "Suzie's child",
          'tag' => [
            'id' => '123',
            'type' => 'ear_tag',
            'location' => 'Left ear',
          ],
          'sex' => 'F',
          'weight' => '10',
          'notes' => [
            'value' => 'Child 1 notes',
            'format' => 'default',
          ],
          'survived' => TRUE,
        ],
        [
          'name' => 'Child 2',
          // A checkbox with a #default_value of TRUE must pass NULL in order
          // to be treated as FALSE due to the core checkbox element value
          // callback logic. Setting this to FALSE or 0 does not work.
          // @see \Drupal\Core\Render\Element\Checkbox::valueCallback()
          'survived' => NULL,
        ],
      ],
      'birth_mother' => $birth_mother->label(),
      'genetic_mother' => $genetic_mother->label(),
      'genetic_father' => $genetic_father->label(),
      'group' => $group->label(),
      'notes' => [
        'value' => 'Birth notes',
        'format' => 'default',
      ],
    ]);

    // Load assets and logs.
    $assets = $this->assetStorage->loadMultiple();
    $logs = $this->logStorage->loadMultiple();

    // Confirm that seven assets (5 animals + 1 land + 1 group) and three logs
    // (1 birth + 2 observations) exists.
    $this->assertCount(7, $assets);
    $this->assertCount(3, $logs);

    // Confirm that the first child animal asset contains all the expected data.
    $child1 = $assets[6];
    $this->assertEquals("Suzie's child", $child1->label());
    $this->assertEquals($breed2->id(), $child1->get('animal_type')->target_id);
    $this->assertEquals($today->getTimestamp(), $child1->get('birthdate')->value);
    $this->assertEquals('F', $child1->get('sex')->value);
    $this->assertEquals('ear_tag', $child1->get('id_tag')[0]->type);
    $this->assertEquals('123', $child1->get('id_tag')[0]->id);
    $this->assertEquals('Left ear', $child1->get('id_tag')[0]->location);
    $parents = $child1->get('parent')->referencedEntities();
    $this->assertCount(2, $parents);
    $this->assertEquals($genetic_mother->id(), $parents[0]->id());
    $this->assertEquals($genetic_father->id(), $parents[1]->id());
    $this->assertEquals('Child 1 notes', $child1->get('notes')->value);
    $this->assertEquals('active', $child1->get('status')->value);
    $child_location = $this->assetLocation->getLocation($child1);
    $this->assertEquals($location->id(), reset($child_location)->id());
    $child_group = $this->groupMembership->getGroup($child1);
    $this->assertEquals($group->id(), reset($child_group)->id());

    // Confirm that the second child animal asset contains all the expected
    // data.
    $child2 = $assets[7];
    $this->assertEquals('Child 2', $child2->label());
    $this->assertEquals($breed2->id(), $child2->get('animal_type')->target_id);
    $this->assertEquals($today->getTimestamp(), $child2->get('birthdate')->value);
    $this->assertEquals('', $child2->get('sex')->value);
    $parents = $child2->get('parent')->referencedEntities();
    $this->assertCount(2, $parents);
    $this->assertEquals($genetic_mother->id(), $parents[0]->id());
    $this->assertEquals($genetic_father->id(), $parents[1]->id());
    $this->assertEquals('archived', $child2->get('status')->value);
    $child_location = $this->assetLocation->getLocation($child2);
    $this->assertEquals($location->id(), reset($child_location)->id());
    $child_group = $this->groupMembership->getGroup($child2);
    $this->assertEquals($group->id(), reset($child_group)->id());

    // Confirm that the weight observation log contains all the expected data.
    $weight_log = $logs[2];
    $this->assertEquals('observation', $weight_log->bundle());
    $this->assertEquals($today->getTimestamp(), $weight_log->get('timestamp')->value);
    $this->assertEquals("Weight of Suzie's child is 10 kg", $weight_log->label());
    $this->assertEquals($child1->id(), $weight_log->get('asset')->referencedEntities()[0]->id());
    $this->assertEquals('weight', $weight_log->get('quantity')->referencedEntities()[0]->get('measure')->value);
    $this->assertEquals('10', $weight_log->get('quantity')->referencedEntities()[0]->get('value')[0]->get('decimal')->getValue());
    $this->assertEquals('kg', $weight_log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->get('name')->value);
    $this->assertEquals('done', $weight_log->get('status')->value);

    // Confirm that the birth log contains all the expected data.
    $birth_log = $logs[3];
    $this->assertEquals('birth', $birth_log->bundle());
    $this->assertEquals($today->getTimestamp(), $birth_log->get('timestamp')->value);
    $this->assertEquals("Birth: Suzie's child, Child 2", $birth_log->label());
    $this->assertEquals($child1->id(), $birth_log->get('asset')->referencedEntities()[0]->id());
    $this->assertEquals($child2->id(), $birth_log->get('asset')->referencedEntities()[1]->id());
    $this->assertEquals($birth_mother->id(), $birth_log->get('mother')->referencedEntities()[0]->id());
    $this->assertEquals($location->id(), $birth_log->get('location')[0]->target_id);
    $this->assertEquals(TRUE, $birth_log->get('is_movement')->value);
    $this->assertEquals($group->id(), $birth_log->get('group')[0]->target_id);
    $this->assertEquals(TRUE, $birth_log->get('is_group_assignment')->value);
    $this->assertEquals('done', $birth_log->get('status')->value);
    $this->assertEquals('Birth notes', $birth_log->get('notes')->value);
  }

}
