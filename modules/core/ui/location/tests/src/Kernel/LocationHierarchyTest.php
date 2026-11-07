<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_location\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\asset\Entity\Asset;
use Drupal\farm_ui_location\Form\LocationHierarchyForm;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for the location hierarchy form.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class LocationHierarchyTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity',
    'farm_field',
    'farm_location',
    'farm_location_test',
    'farm_log',
    'farm_log_asset',
    'farm_map',
    'farm_parent',
    'farm_ui_location',
    'geofield',
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
    $this->installConfig(['farm_location_test']);
    $this->setUpCurrentUser([], ['view any asset']);
  }

  /**
   * Test that archived locations are not listed.
   */
  public function testArchivedLocationsNotListed() {

    // Create one active and one archived location.
    Asset::create(['type' => 'location', 'name' => 'Active', 'is_location' => TRUE])->save();
    Asset::create(['type' => 'location', 'name' => 'Archived', 'is_location' => TRUE, 'archived' => TRUE])->save();

    // Confirm that the form lists only the active location.
    $form = $this->container->get('form_builder')->getForm(LocationHierarchyForm::class);
    $locations = $form['#attached']['drupalSettings']['asset_tree'][0]['children'];
    $this->assertEquals(['Active'], array_column($locations, 'text'));
  }

}
