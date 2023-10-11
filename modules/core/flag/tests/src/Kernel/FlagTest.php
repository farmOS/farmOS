<?php

namespace Drupal\Tests\farm_flag\Kernel;

use Drupal\farm_flag\Entity\FarmFlag;
use Drupal\Tests\token\Kernel\KernelTestBase;

/**
 * Tests for farm_flag logic.
 *
 * @group farm_flag
 */
class FlagTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_field',
    'farm_flag',
    'log',
    'asset',
    'state_machine',
  ];

  /**
   * Test farm flag options logic.
   */
  public function testFarmFlagOptions() {

    // Create a general flag that applies to all entity types.
    $general_flag = FarmFlag::create([
      'id' => 'general',
      'label' => 'General',
      'entity_types' => NULL,
    ]);
    $general_flag->save();

    // Create bundles and flags for testing.
    $test_entity_types = [
      'log' => ['activity', 'input', 'observation'],
      'asset' => [],
    ];
    foreach ($test_entity_types as $entity_type => $bundles) {
      $entity_type_id = $entity_type . '_type';

      // Create a flag for all bundles of the entity type.
      $flag = FarmFlag::create([
        'id' => $entity_type . '_flag',
        'entity_types' => [
          $entity_type => ['all'],
        ],
      ]);
      $flag->save();

      // Create bundles and a flag for each bundle.
      foreach ($bundles as $bundle_id) {

        // Create the bundle.
        $bundle = \Drupal::entityTypeManager()->getStorage($entity_type_id)->create([
          'id' => $bundle_id,
          'workflow' => $entity_type . '_default',
        ]);
        $bundle->save();

        // Create a flag that only applies for the bundle.
        $flag = FarmFlag::create([
          'id' => $bundle_id . '_flag',
          'entity_types' => [
            $entity_type => [$bundle_id],
          ],
        ]);
        $flag->save();
      }
    }

    // Create a special flag that only applies to activity logs.
    $flag = FarmFlag::create([
      'id' => 'special_flag',
      'entity_types' => [
        'log' => ['activity'],
      ],
    ]);
    $flag->save();

    // Load all flag options.
    $all_flags = \Drupal::entityTypeManager()->getStorage('flag')->loadMultiple();
    $all_flag_ids = array_keys($all_flags);

    // 1. With default parameters all flag options are returned.
    $expected_flag_ids = array_keys(farm_flag_options());
    $this->assertEmpty(array_diff($expected_flag_ids, $all_flag_ids), 'All flag options are returned.');

    // 2. Flags applying to any asset type are returned.
    $flag_ids = array_keys(farm_flag_options('asset'));
    $expected_flag_ids = ['general', 'asset_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));

    // 3. Flags applying to any log type are returned.
    $flag_ids = array_keys(farm_flag_options('log'));
    $expected_flag_ids = ['general', 'log_flag', 'special_flag', 'activity_flag', 'input_flag', 'observation_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));

    // 4. Flags applying to every log type are returned.
    $flag_ids = array_keys(farm_flag_options('log', [], TRUE));
    $expected_flag_ids = ['general', 'log_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));

    // 5. Flags applying to either activity or input log types are returned.
    $flag_ids = array_keys(farm_flag_options('log', ['activity', 'input']));
    $expected_flag_ids = ['general', 'log_flag', 'special_flag', 'activity_flag', 'input_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));

    // 6. Flags applying to both activity and input log types are returned.
    $flag_ids = array_keys(farm_flag_options('log', ['activity', 'input'], TRUE));
    $expected_flag_ids = ['general', 'log_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));

    // 7. Flags applying to only the activity log types are returned.
    $flag_ids = array_keys(farm_flag_options('log', ['activity'], TRUE));
    $expected_flag_ids = ['general', 'log_flag', 'special_flag', 'activity_flag'];
    $this->assertEmpty(array_diff($expected_flag_ids, $flag_ids));
  }

}
