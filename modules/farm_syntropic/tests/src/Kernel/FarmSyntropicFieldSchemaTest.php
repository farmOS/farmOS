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
   * All 6 Infrastructure custom fields exist.
   */
  public function testInfrastructureFieldsRegistered(): void {
    $expected = [
      'infrastructure_type',
      'material',
      'capacity',
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

}
