<?php

namespace Drupal\Tests\farm_quick\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for farmOS quick forms.
 *
 * @group farm
 */
class QuickFormTest extends KernelTestBase {

  /**
   * The quick form manager.
   *
   * @var \Drupal\farm_quick\QuickFormManager
   */
  protected $quickFormManager;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_quick',
    'farm_quick_test',
    'log',
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->quickFormManager = \Drupal::service('plugin.manager.quick_form');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_quick_test',
    ]);
  }

  /**
   * Test quick form discovery.
   */
  public function testQuickFormDiscovery() {

    // Load quick form definitions.
    $quick_forms = $this->quickFormManager->getDefinitions();

    // Confirm that one quick form was discovered.
    $this->assertEquals(1, count($quick_forms));

    // Initialize the test quick form.
    /** @var \Drupal\farm_quick\QuickFormInterface $test_quick_form */
    $test_quick_form = $this->quickFormManager->createInstance('test');

    // Confirm the label, description, helpText, and permissions.
    $this->assertEquals('Test quick form', $test_quick_form->getLabel());
    $this->assertEquals('Test quick form description.', $test_quick_form->getDescription());
    $this->assertEquals('Test quick form help text.', $test_quick_form->getHelpText());
    $this->assertEquals(['create test log'], $test_quick_form->getPermissions());
  }

}
