<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\taxonomy\Entity\Term;

/**
 * Tests for CSV import database table.
 *
 * @group farm
 */
class CsvImportDatabaseTest extends CsvImportTestBase {

  /**
   * Define the CSV import entity table name.
   *
   * @var string
   */
  protected $tableName = 'farm_import_csv_entity';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_animal_type',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_animal_type']);
    $this->installConfig(['farm_import_csv_test']);
  }

  /**
   * Test farm_import_csv_entity database table.
   */
  public function testDatabaseTable() {

    // Run CSV import.
    $this->importCsv('animal-types.csv', 'csv_taxonomy_term:animal_type');

    // Confirm that the expected rows exist in the table.
    $expected_rows = [
      [
        'entity_type' => 'taxonomy_term',
        'entity_id' => '1',
        'migration' => 'csv_taxonomy_term:animal_type',
        'file_id' => '1',
        'rownum' => '1',
      ],
      [
        'entity_type' => 'taxonomy_term',
        'entity_id' => '2',
        'migration' => 'csv_taxonomy_term:animal_type',
        'file_id' => '1',
        'rownum' => '2',
      ],
      [
        'entity_type' => 'taxonomy_term',
        'entity_id' => '3',
        'migration' => 'csv_taxonomy_term:animal_type',
        'file_id' => '1',
        'rownum' => '3',
      ],
      [
        'entity_type' => 'taxonomy_term',
        'entity_id' => '4',
        'migration' => 'csv_taxonomy_term:animal_type',
        'file_id' => '1',
        'rownum' => '4',
      ],
    ];
    $result = \Drupal::database()->select($this->tableName, 't')->fields('t')->orderBy('t.file_id')->orderBy('t.rownum')->execute()->fetchAll();
    foreach ($result as $i => $row) {
      $this->assertEquals($expected_rows[$i]['entity_type'], $row->entity_type);
      $this->assertEquals($expected_rows[$i]['entity_id'], $row->entity_id);
      $this->assertEquals($expected_rows[$i]['migration'], $row->migration);
      $this->assertEquals($expected_rows[$i]['file_id'], $row->file_id);
      $this->assertEquals($expected_rows[$i]['rownum'], $row->rownum);
    }

    // Delete the first taxonomy_term, and confirm that its row was removed.
    $count = \Drupal::database()->select($this->tableName, 't')->fields('t')->condition('t.entity_type', 'taxonomy_term')->condition('t.entity_id', 1)->countQuery()->execute()->fetchField();
    $this->assertEquals(1, $count);
    $term = Term::load(1);
    $term->delete();
    $count = \Drupal::database()->select($this->tableName, 't')->fields('t')->condition('t.entity_type', 'log')->condition('t.entity_id', 1)->countQuery()->execute()->fetchField();
    $this->assertEquals(0, $count);
  }

}
