<?php

namespace Drupal\Tests\farm_quick\Kernel;

use Drupal\Core\Form\FormState;
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
    'asset',
    'farm_field',
    'farm_quantity_standard',
    'farm_quick',
    'farm_quick_test',
    'farm_unit',
    'fraction',
    'log',
    'options',
    'quantity',
    'state_machine',
    'taxonomy',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->quickFormManager = \Drupal::service('plugin.manager.quick_form');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('quantity');
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
    /** @var \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface $test_quick_form */
    $test_quick_form = $this->quickFormManager->createInstance('test');

    // Confirm the label, description, helpText, and permissions.
    $this->assertEquals('Test quick form', $test_quick_form->getLabel());
    $this->assertEquals('Test quick form description.', $test_quick_form->getDescription());
    $this->assertEquals('Test quick form help text.', $test_quick_form->getHelpText());
    $this->assertEquals(['create test log'], $test_quick_form->getPermissions());
  }

  /**
   * Test quick form submission.
   */
  public function testQuickFormSubmission() {

    // Programmatically submit the test quick form.
    $form_state = (new FormState())->setValues([
      'count' => '12',
    ]);
    \Drupal::formBuilder()->submitForm('\Drupal\farm_quick\Form\QuickForm', $form_state, 'test');

    // Load the form state storage.
    $storage = $form_state->getStorage();

    // Confirm that an asset was created.
    $this->assertNotEmpty($storage['assets'][0]->id());

    // Confirm that the asset is linked to the quick form.
    $this->assertEquals('test', $storage['assets'][0]->get('quick')[0]->value);

    // Confirm that a log was created.
    $this->assertNotEmpty($storage['logs'][0]->id());

    // Confirm that the log is linked to the quick form.
    $this->assertEquals('test', $storage['logs'][0]->get('quick')[0]->value);

    // Confirm that a quantity was created.
    $this->assertNotEmpty($storage['quantities'][0]->id());

    // Confirm that three terms were created or loaded.
    $this->assertEquals(3, count($storage['terms']));
    foreach ($storage['terms'] as $term) {
      $this->assertNotEmpty($term->id());
    }

    // Confirm that the second and third terms have the same ID.
    $match = $storage['terms'][1]->id() == $storage['terms'][2]->id();
    $this->assertTrue($match);
  }

}
