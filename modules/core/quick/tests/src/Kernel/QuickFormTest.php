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
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity_reference_revisions',
    'farm_field',
    'farm_log_quantity',
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
    $this->quickFormInstanceManager = \Drupal::service('quick_form.instance_manager');
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

    // Load quick forms.
    /** @var \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface[] $quick_forms */
    $quick_forms = $this->quickFormInstanceManager->getInstances();

    // Confirm that one quick form was discovered.
    $this->assertEquals(1, count($quick_forms));

    // Confirm the label, description, helpText, and permissions.
    $this->assertEquals('Test quick form', $quick_forms['test']->getLabel());
    $this->assertEquals('Test quick form description.', $quick_forms['test']->getDescription());
    $this->assertEquals('Test quick form help text.', $quick_forms['test']->getHelpText());
    $this->assertEquals(['create test log'], $quick_forms['test']->getPermissions());
  }

  /**
   * Test quick form submission.
   */
  public function testQuickFormSubmission() {

    // Programmatically submit the test quick form.
    $form_state = (new FormState())->setValues([
      'test' => '12',
    ]);
    \Drupal::formBuilder()->submitForm('\Drupal\farm_quick\Form\QuickForm', $form_state, 'test');

    // Load the form state storage.
    $storage = $form_state->getStorage();

    // Confirm that an asset was created.
    $this->assertNotEmpty($storage['assets'][0]->id());

    // Confirm that the asset is linked to the quick form.
    $this->assertEquals('test', $storage['assets'][0]->quick[0]);

    // Confirm that a log was created.
    $this->assertNotEmpty($storage['logs'][0]->id());

    // Confirm that the log is linked to the quick form.
    $this->assertEquals('test', $storage['logs'][0]->quick[0]);

    // Confirm that the log's quantity type is test.
    $this->assertEquals('test', $storage['logs'][0]->get('quantity')->referencedEntities()[0]->bundle());

    // Confirm that a quantity was created and its type is test2.
    $this->assertNotEmpty($storage['quantities'][0]->id());
    $this->assertEquals('test2', $storage['quantities'][0]->bundle());

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
