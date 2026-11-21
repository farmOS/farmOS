<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\organization\Entity\Organization;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for organization CSV importers.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class OrganizationCsvImportTest extends CsvImportTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'organization',
    'organization_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('organization');
    $this->installConfig(['organization_test']);
  }

  /**
   * Test organization CSV importer.
   */
  public function testOrganizationCsvImport(): void {

    // Run the CSV import against the default bundle.
    $this->importCsv('organizations.csv', 'csv_organization:default');

    // Confirm that organizations have been created with the expected
    // values.
    $orgs = Organization::loadMultiple();
    $this->assertCount(3, $orgs);

    $expected_values = [
      1 => [
        'name' => 'Green Acres Farm',
        'notes' => 'A small organic vegetable farm in the valley.',
      ],
      2 => [
        'name' => 'Sunrise Dairy',
        'notes' => 'Dairy operation specializing in raw milk and cheese, established 1987.',
      ],
      3 => [
        'name' => 'Old River Ranch',
        'notes' => 'Large cattle ranch.',
      ],
    ];

    foreach ($orgs as $id => $org) {
      $this->assertEquals('default', $org->bundle());
      $this->assertEquals($expected_values[$id]['name'], $org->label());
      $this->assertEquals($expected_values[$id]['notes'], $org->get('notes')->value);
      $this->assertEquals('default', $org->get('notes')->format);
    }
  }

}
