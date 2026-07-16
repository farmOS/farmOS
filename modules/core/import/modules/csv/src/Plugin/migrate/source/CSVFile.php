<?php

declare(strict_types=1);

namespace Drupal\farm_import_csv\Plugin\migrate\source;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Attribute\MigrateSource;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Source for CSV file entities.
 *
 * This extends the CSV source plugin provided by migrate_source_csv, and
 * automatically handles assigning a file entity ID and row number to the
 * unique IDs of each row.
 */
#[MigrateSource(
  id: 'csv_file',
)]
class CSVFile extends CSV implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MigrationInterface $migration,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {

    // Uniqueness of rows will be determined by file ID + row number, so we
    // explicitly set create_record_number and ids in the source configuration
    // before passing it to the parent constructor.
    $configuration['create_record_number'] = TRUE;
    $configuration['ids'] = ['file_id', 'record_number'];

    // Delegate to the parent for everything else.
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, ?MigrationInterface $migration = NULL) {
    // @todo Use autowiring and remove this when the parent class does.
    // @see https://www.drupal.org/project/drupal/issues/3552110
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getGenerator(\Iterator $records) {

    // Attempt to look up the file entity ID from the file path and assign it
    // to a "file_id" column on every record, which will be used as one of the
    // migration source IDs (alongside row number).
    $files = $this->entityTypeManager->getStorage('file')->loadByProperties(['uri' => $this->configuration['path']]);
    if (empty($files)) {
      throw new MigrateException('Could not find the uploaded CSV file.');
    }
    $file = reset($files);

    // We duplicate (and simplify) the logic from parent::getGenerator()
    // for setting the record_number column (because we can't inherit from a
    // parent generator method), and add the file ID.
    $record_num = $this->configuration['header_offset'] ?? 0;
    foreach ($records as $record) {
      $record[$this->configuration['record_number_field']] = ++$record_num;
      $record['file_id'] = $file->id();
      yield $record;
    }
  }

}
