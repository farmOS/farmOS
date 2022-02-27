<?php

namespace Drupal\Tests\farm_quick_weight\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\farm_quick\Kernel\QuickFormTestBase;

/**
 * Tests for farmOS weight quick form.
 *
 * @group farm
 */
class QuickWeightTests extends QuickFormTestBase {

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId = 'weight';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_animal',
    'farm_id_tag',
    'farm_observation',
    'farm_quantity_standard',
    'farm_quick_weight',
    'farm_unit',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig([
      'farm_animal',
      'farm_observation',
      'farm_quantity_standard',
    ]);
  }

  /**
   * Test weight quick form submission.
   */
  public function testQuickBirth() {

    // Create three animals (two females of different breeds, one male).
    $animal1 = Asset::create([
      'name' => 'Animal 1',
      'type' => 'animal',
      'sex' => 'F',
    ]);
    $animal->save();

    $unit = Term::create([
      'name' => 'kg',
      'vid' => 'kg',
    ]);
    $unit->save();

    // Submit the birth quick form.
    $this->submitQuickForm([
      'animal' => ['target_id' => $animal1->id()],
      'weight' => '12.5',
      'unit' => ['target_id' => $unit->id()],
    ]);

    // Check the log.
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(1, $logs);

    $log = $logs[1];
    $this->assertEquals($log->getType(), 'observation');
    $this->assertEquals($log->getName(), $this->t("Weight of @asset is @weight @unit", [
      '@asset' => $animal1->label(),
      '@weight' => '12.5',
      '@unit' => $unit->label(),
    ]));
  }

}
