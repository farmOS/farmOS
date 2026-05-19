<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_syntropic\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\asset\Entity\Asset;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Field-schema tests for the farm_syntropic module.
 *
 * Verifies that:
 * - Both custom asset bundles (tree, infrastructure) register.
 * - Every custom field declared in the plugin classes is present on its
 *   bundle's field definitions.
 * - The four custom taxonomy vocabularies install from config.
 * - The min/max range constraints on Tree decimal fields actually
 *   trigger validation errors when violated (and pass when respected).
 *
 * These are cheap, fast guard rails that catch regressions like:
 * - Forgetting to call parent::buildFieldDefinitions() in a future edit.
 * - Misnaming a field key (typo in 'species' becomes a silent loss of
 *   data on JSON:API writes).
 * - Removing a range bound and not noticing.
 *
 * NOTE: Per the PR #3 CI scoping policy this test runs against ONLY our
 * module. Anything inherited from upstream farmOS is not tested here.
 */
#[Group('farm_syntropic')]
#[RunTestsInSeparateProcesses]
class FarmSyntropicFieldSchemaTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   *
   * Disable strict config schema validation. This class exercises field
   * BEHAVIOR (registration, range validation), not config schema
   * correctness. Strict checking would require declaring a per-bundle
   * config schema YAML for every asset_type we ship, which is outside
   * this test's scope. The smoke test workflow validates the live config
   * against a real farmOS install end-to-end.
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    // Drupal core + general contrib.
    'system',
    'user',
    'options',
    'taxonomy',
    'text',
    'views',
    // Asset + log entity types.
    'asset',
    'log',
    'state_machine',
    // farmOS field + entity infrastructure.
    'entity',
    'entity_reference_revisions',
    'entity_reference_validators',
    'farm_entity',
    'farm_entity_access',
    'farm_field',
    'farm_log',
    'farm_log_asset',
    // Geometry support (the asset type provides intrinsic_geometry).
    'farm_location',
    'farm_map',
    'fraction',
    'geofield',
    // farm_plant_type provides the 'plant_type' vocabulary the Tree's
    // 'species' field references.
    'farm_plant_type',
    // Our module under test.
    'farm_syntropic',
  ];

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $fieldManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installEntitySchema('taxonomy_term');
    $this->installConfig([
      'farm_syntropic',
    ]);
    $this->fieldManager = \Drupal::service('entity_field.manager');
  }

  /**
   * Both custom asset bundles register from config/install.
   */
  public function testAssetBundlesRegistered(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('asset_type');
    foreach (['tree', 'infrastructure'] as $bundle_id) {
      $bundle = $storage->load($bundle_id);
      $this->assertNotNull($bundle, "Asset bundle '$bundle_id' should be registered");
    }
  }

  /**
   * All 13 Tree custom fields exist.
   *
   * If you add or rename a field on the Tree asset type plugin, update
   * this list. The intent of the test is to make field-name changes
   * visible (they're a JSON:API breaking change).
   */
  public function testTreeFieldsRegistered(): void {
    $expected = [
      'species',
      'variety',
      'dbh_cm',
      'height_m',
      'canopy_radius_m',
      'stratum',
      'succession_stage',
      'health_status',
      'rootstock',
      'graft_variety',
      'planting_date',
      'source',
      'odoo_lot',
    ];
    $actual = array_keys($this->fieldManager->getFieldDefinitions('asset', 'tree'));
    $missing = array_diff($expected, $actual);
    $this->assertEmpty(
      $missing,
      'Missing Tree fields: ' . implode(', ', $missing),
    );
  }

  /**
   * All 7 Infrastructure custom fields exist.
   *
   * If you add or rename a field on the Infrastructure asset type plugin,
   * update this list. The intent of the test is to make field-name changes
   * visible (they're a JSON:API breaking change).
   */
  public function testInfrastructureFieldsRegistered(): void {
    $expected = [
      'infrastructure_type',
      'material',
      'capacity_value',
      'capacity_unit',
      'installation_date',
      'condition',
      'specifications',
    ];
    $actual = array_keys($this->fieldManager->getFieldDefinitions('asset', 'infrastructure'));
    $missing = array_diff($expected, $actual);
    $this->assertEmpty(
      $missing,
      'Missing Infrastructure fields: ' . implode(', ', $missing),
    );
  }

  /**
   * The capacity_unit list_string has exactly the 10 expected allowed values.
   *
   * Guards against accidental truncation of the controlled vocabulary AND
   * against accidental drift between Infrastructure::capacityFieldOptions()
   * and what actually reaches the field manager.
   */
  public function testCapacityUnitAllowedValues(): void {
    $field = $this->fieldManager->getFieldDefinitions('asset', 'infrastructure')['capacity_unit'];
    $allowed = $field->getSetting('allowed_values');
    $this->assertCount(
      10,
      $allowed,
      'capacity_unit should have exactly 10 allowed values',
    );
    // Spot-check one key from each measurement family — power, flow,
    // volume, electrical, length. Missing any would indicate truncation.
    foreach (['watts', 'gpm', 'gallons', 'volts', 'linear_meters'] as $key) {
      $this->assertArrayHasKey(
        $key,
        $allowed,
        "capacity_unit allowed_values should contain '$key'",
      );
    }
  }

  /**
   * Negative values on capacity_value are rejected; zero and positive pass.
   *
   * Mirrors testDecimalRangeValidation() for the Tree asset type.
   * FarmFieldFactory enforces the min:0 setting via entity validation, which
   * gates both the form widget and JSON:API writes.
   */
  public function testCapacityValueRangeValidation(): void {
    $infra = Asset::create([
      'type' => 'infrastructure',
      'name' => 'Capacity range probe',
      'infrastructure_type' => 'solar',
      'capacity_value' => '-1',
    ]);
    $violations = $infra->validate();
    $this->assertGreaterThan(
      0,
      $violations->getByField('capacity_value')->count(),
      'Expected a validation violation for capacity_value = -1',
    );

    // Zero is on the boundary — must be accepted.
    $infra->set('capacity_value', '0');
    $violations = $infra->validate();
    $this->assertSame(
      0,
      $violations->getByField('capacity_value')->count(),
      'capacity_value = 0 should not produce a violation',
    );

    // Positive value must be accepted.
    $infra->set('capacity_value', '400.000');
    $violations = $infra->validate();
    $this->assertSame(
      0,
      $violations->getByField('capacity_value')->count(),
      'capacity_value = 400.000 should not produce a violation',
    );
  }

  /**
   * All four custom taxonomy vocabularies install.
   */
  public function testTaxonomyVocabulariesRegistered(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary');
    foreach ([
      'syntropic_stratum',
      'succession_stage',
      'tree_health',
      'infrastructure_type',
    ] as $vid) {
      $this->assertNotNull(
        $storage->load($vid),
        "Vocabulary '$vid' should be registered",
      );
    }
  }

  /**
   * Negative values on DBH/height/canopy_radius are rejected by validation.
   *
   * Range bounds are declared via the FarmFieldFactory's 'min'/'max'
   * shorthand which Drupal enforces in both the form widget and entity
   * validation (so the JSON:API write path is also gated).
   */
  public function testDecimalRangeValidation(): void {
    $cases = [
      ['field' => 'dbh_cm', 'bad' => '-5'],
      ['field' => 'height_m', 'bad' => '-1'],
      ['field' => 'canopy_radius_m', 'bad' => '-0.5'],
    ];
    foreach ($cases as $case) {
      $tree = Asset::create([
        'type' => 'tree',
        'name' => 'Validation probe',
        $case['field'] => $case['bad'],
      ]);
      $violations = $tree->validate();
      $field_violations = $violations->getByField($case['field']);
      $this->assertGreaterThan(
        0,
        $field_violations->count(),
        "Expected a validation violation for {$case['field']} = {$case['bad']}",
      );
    }
  }

  /**
   * A tree with valid in-range measurements passes validation.
   *
   * Sanity check: ensures the bounds aren't so tight that legitimate
   * data is rejected.
   */
  public function testValidTreeMeasurementsPass(): void {
    $tree = Asset::create([
      'type' => 'tree',
      'name' => 'Valid probe',
      'dbh_cm' => '25.50',
      'height_m' => '8.20',
      'canopy_radius_m' => '3.10',
    ]);
    $violations = $tree->validate();
    foreach (['dbh_cm', 'height_m', 'canopy_radius_m'] as $f) {
      $this->assertSame(
        0,
        $violations->getByField($f)->count(),
        "Field '$f' should not produce a validation violation on valid input",
      );
    }
  }

  /**
   * tree_planting bundle registers from config/install.
   */
  public function testTreePlantingBundleRegistered(): void {
    $storage = \Drupal::entityTypeManager()->getStorage('asset_type');
    $bundle = $storage->load('tree_planting');
    $this->assertNotNull($bundle, "Asset bundle 'tree_planting' should be registered");
    $this->assertSame('Tree Planting', $bundle->label());
  }

  /**
   * All 8 TreePlanting custom fields exist.
   */
  public function testTreePlantingFieldsRegistered(): void {
    $expected = [
      'species', 'variety', 'tree_count', 'spacing_m',
      'geometry', 'planting_date', 'source', 'notes',
    ];
    $actual = array_keys($this->fieldManager->getFieldDefinitions('asset', 'tree_planting'));
    $missing = array_diff($expected, $actual);
    $this->assertEmpty(
      $missing,
      'Missing TreePlanting fields: ' . implode(', ', $missing),
    );
  }

  /**
   * The parent_planting field exists on the Tree bundle and is bundle-constrained.
   */
  public function testTreeParentPlantingFieldRegistered(): void {
    $fields = $this->fieldManager->getFieldDefinitions('asset', 'tree');
    $this->assertArrayHasKey(
      'parent_planting',
      $fields,
      "Tree bundle should have a 'parent_planting' field",
    );
    $definition = $fields['parent_planting'];
    $this->assertSame('asset', $definition->getSetting('target_type'));
    $handler_settings = $definition->getSetting('handler_settings');
    $this->assertArrayHasKey('target_bundles', $handler_settings);
    $this->assertSame(
      ['tree_planting' => 'tree_planting'],
      $handler_settings['target_bundles'],
      'parent_planting handler_settings must constrain to tree_planting bundle',
    );
  }

  /**
   * tree_count = 0 produces a violation; tree_count = 1 passes.
   */
  public function testTreePlantingTreeCountValidation(): void {
    $planting_zero = Asset::create([
      'type' => 'tree_planting',
      'name' => 'Validation probe - zero count',
      'tree_count' => 0,
    ]);
    $violations = $planting_zero->validate();
    $this->assertGreaterThan(
      0,
      $violations->getByField('tree_count')->count(),
      'tree_count = 0 should produce a validation violation (min is 1)',
    );

    $planting_one = Asset::create([
      'type' => 'tree_planting',
      'name' => 'Validation probe - valid count',
      'tree_count' => 1,
    ]);
    $violations = $planting_one->validate();
    $this->assertSame(
      0,
      $violations->getByField('tree_count')->count(),
      'tree_count = 1 should not produce a validation violation',
    );
  }

}
