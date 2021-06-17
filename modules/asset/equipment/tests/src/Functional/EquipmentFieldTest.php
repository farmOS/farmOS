<?php

namespace Drupal\Tests\farm_equipment\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the equipment used field.
 *
 * @group farm
 */
class EquipmentFieldTest extends FarmBrowserTestBase {

  use StringTranslationTrait;

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
    'farm_entity',
    'farm_equipment',
    'farm_equipment_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with permission to administer assets and logs.
    $this->user = $this->createUser(['administer assets', 'administer log']);
    $this->drupalLogin($this->user);
  }

  /**
   * Test that the Equipment field is added to logs and visible.
   */
  public function testEquipmentField() {
    $entity_type_manager = $this->container->get('entity_type.manager');
    $asset_storage = $entity_type_manager->getStorage('asset');
    $log_storage = $entity_type_manager->getStorage('log');

    // Create an equipment asset.
    $asset = $asset_storage->create([
      'name' => $this->randomMachineName(),
      'type' => 'equipment',
    ]);
    $asset->save();

    // Go to the log add form.
    $this->drupalGet('log/add/test');
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the equipment reference field form is visible.
    $this->assertSession()->fieldExists('equipment[0][target_id]');

    // Create a log that references the equipment.
    $log = $log_storage->create(['type' => 'test']);
    $log->equipment[] = ['target_id' => $asset->id()];
    $log->save();

    // Go to the log view page.
    $this->drupalGet('log/' . $log->id());
    $this->assertSession()->statusCodeEquals(200);

    // Confirm that the equipment reference field display is visible.
    $this->assertSession()->pageTextContains("Equipment used");
  }

}
