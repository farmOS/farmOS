<?php

namespace Drupal\Tests\farm_input\Functional;

use Drupal\log\Entity\Log;
use Drupal\quantity\Entity\Quantity;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Test material type filter functionality.
 *
 * @see \Drupal\farm_input\Plugin\views\filter\LogQuantityMaterialType
 *
 * @group farm
 */
class InputViewMaterialTypeTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * Material type terms.
   *
   * @var \Drupal\taxonomy\Entity\Term[]
   */
  protected array $materialTypes;

  /**
   * Logs for testing.
   *
   * @var \Drupal\log\Entity\Log[]
   */
  protected array $testLogs;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_ui',
    'farm_quantity_standard',
    'farm_input',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    // Create and login a user with permission to view logs.
    $this->user = $this->createUser(['view any log']);
    $this->drupalLogin($this->user);

    // Create material type terms.
    $material_type_names = ['compost', 'fertilizer', 'spray'];
    $this->materialTypes = [];
    foreach ($material_type_names as $term_name) {
      $term = Term::create(['vid' => 'material_type', 'name' => $term_name]);
      $term->save();
      $this->materialTypes[$term_name] = $term;
    }

    // Create logs with quantities.
    $test_log_definitions = [
      // Input log with no quantities.
      [
        'log' => ['type' => 'input'],
        'quantities' => [],
      ],
      // Input log with standard quantity.
      [
        'log' => ['type' => 'input'],
        'quantities' => [
          ['type' => 'standard', 'measure' => 'count'],
        ],
      ],
      // Input log with compost material type quantity.
      [
        'log' => ['type' => 'input'],
        'quantities' => [
          ['type' => 'material', 'measure' => 'weight', 'material_type' => $this->materialTypes['compost']],
        ],
      ],
      // Input log with fertilizer material type quantity.
      [
        'log' => ['type' => 'input'],
        'quantities' => [
          ['type' => 'material', 'measure' => 'weight', 'material_type' => $this->materialTypes['fertilizer']],
        ],
      ],
      // Input log with multiple quantities.
      [
        'log' => ['type' => 'input'],
        'quantities' => [
          ['type' => 'standard', 'measure' => 'count'],
          ['type' => 'material', 'measure' => 'weight', 'material_type' => $this->materialTypes['compost']],
          ['type' => 'material', 'measure' => 'weight', 'material_type' => $this->materialTypes['fertilizer']],
        ],
      ],
    ];
    $this->testLogs = [];
    foreach ($test_log_definitions as $log_definition) {
      $this->createLogWithQuantities($log_definition['log'], $log_definition['quantities']);
    }

  }

  /**
   * Test material type filter functionality.
   */
  public function testMaterialTypeFilter() {

    // Get the input logs view.
    $this->drupalGet('/logs/input');

    // Assert that the Material type exposed filter exists.
    $this->assertSession()->pageTextContains('Material type');
    $this->assertSession()->fieldExists('edit-quantity-material-type');

    // Assert that each material type is an option.
    foreach ($this->materialTypes as $material_type) {
      $this->assertSession()->pageTextContains($material_type->label());
    }

    // No filter, assert that each log is visible.
    foreach ($this->testLogs as $log) {
      $this->assertSession()->pageTextContainsOnce($log->label());
    }

    // Collect all material type ids.
    $material_type_ids = array_map(function ($term) {
      return $term->id();
    }, $this->materialTypes);

    // Test each material type individually.
    $test_filters = $material_type_ids;

    // Test filtering by all material types.
    $test_filters[] = $material_type_ids;

    // Filter by each material type test.
    foreach ($test_filters as $material_types) {

      // Query expected logs.
      $expected_logs = \Drupal::entityTypeManager()->getStorage('log')->loadByProperties([
        'quantity.entity:quantity.material_type' => $material_types,
      ]);

      // Filter to only include logs with the specified material type.
      // Make sure the parameter is an array.
      $material_types = is_array($material_types) ? $material_types : [$material_types];
      $this->drupalGet('/logs/input', ['query' => ['quantity_material_type' => $material_types]]);

      // Assert that each expected log exists.
      foreach ($expected_logs as $log) {
        $this->assertSession()->pageTextContainsOnce($log->label());
      }
    }
  }

  /**
   * Helper function to create logs with quantities.
   *
   * @param array $log
   *   The log definition.
   * @param array $quantities
   *   An array of quantity definitions.
   *
   * @return \Drupal\log\Entity\Log
   *   The log that was created.
   */
  protected function createLogWithQuantities(array $log, array $quantities) {

    // Create each quantity.
    $quantity_entities = [];
    foreach ($quantities as $definition) {
      $quantity = Quantity::create($definition);
      $quantity->save();
      $quantity_entities[] = $quantity;
    }

    // Create the log and reference the quantities.
    $log_entity = Log::create($log);
    $log_entity->set('quantity', $quantity_entities);
    $log_entity->save();

    return $log_entity;
  }

}
