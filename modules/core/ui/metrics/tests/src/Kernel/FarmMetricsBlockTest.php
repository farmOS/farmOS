<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_metrics\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\asset\Entity\Asset;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for the farmOS Metrics block.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class FarmMetricsBlockTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity',
    'farm_field',
    'farm_location_test',
    'farm_ui_metrics',
    'log',
    'state_machine',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installConfig(['farm_location_test']);
    $this->setUpCurrentUser([], ['view any asset']);
  }

  /**
   * Test that archived assets are not counted.
   */
  public function testArchivedAssetsNotCounted() {

    // Create one active and one archived asset.
    Asset::create(['type' => 'location', 'name' => 'Active'])->save();
    Asset::create(['type' => 'location', 'name' => 'Archived', 'archived' => TRUE])->save();

    // Confirm that the block counts only the active asset.
    $build = $this->container->get('plugin.manager.block')->createInstance('farm_metrics_block')->build();
    $this->assertEquals('Location: 1', $build['asset']['#items'][0]['#title']);
  }

}
