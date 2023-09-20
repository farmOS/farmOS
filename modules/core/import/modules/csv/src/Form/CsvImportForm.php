<?php

namespace Drupal\farm_import_csv\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Link;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\farm_import_csv\Access\CsvImportMigrationAccess;
use Drupal\file\FileRepositoryInterface;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\migrate\Plugin\MigrationPluginManager;
use Drupal\migrate_source_ui\Form\MigrateSourceUiForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Provides the CSV import form.
 */
class CsvImportForm extends MigrateSourceUiForm {

  /**
   * The file repository service.
   *
   * @var \Drupal\file\FileRepositoryInterface
   */
  protected $fileRepository;

  /**
   * The file usage service.
   *
   * @var \Drupal\file\FileUsage\FileUsageInterface
   */
  protected $fileUsage;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * The CSV import migration access service.
   *
   * @var \Drupal\farm_import_csv\Access\CsvImportMigrationAccess
   */
  protected $migrationAccess;

  /**
   * CsvImportForm constructor.
   *
   * @param \Drupal\migrate\Plugin\MigrationPluginManager $plugin_manager_migration
   *   The migration plugin manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The File System service.
   * @param \Drupal\file\FileRepositoryInterface $file_repository
   *   The file repository service.
   * @param \Drupal\file\FileUsage\FileUsageInterface $file_usage
   *   The file usage service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore service.
   * @param \Drupal\farm_import_csv\Access\CsvImportMigrationAccess $migration_access
   *   The CSV import migration access service.
   */
  public function __construct(MigrationPluginManager $plugin_manager_migration, ConfigFactoryInterface $config_factory, FileSystemInterface $file_system, FileRepositoryInterface $file_repository, FileUsageInterface $file_usage, EntityTypeManagerInterface $entity_type_manager, PrivateTempStoreFactory $temp_store_factory, CsvImportMigrationAccess $migration_access) {
    parent::__construct($plugin_manager_migration, $config_factory, $file_system);
    $this->fileRepository = $file_repository;
    $this->fileUsage = $file_usage;
    $this->entityTypeManager = $entity_type_manager;
    $this->tempStore = $temp_store_factory->get('farm_import_csv');
    $this->migrationAccess = $migration_access;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.migration'),
      $container->get('config.factory'),
      $container->get('file_system'),
      $container->get('file.repository'),
      $container->get('file.usage'),
      $container->get('entity_type.manager'),
      $container->get('tempstore.private'),
      $container->get('farm_import_csv.access'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_import_csv';
  }

  /**
   * Get the title of the migration.
   *
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Returns the migration label.
   */
  public function getTitle(string $migration_id) {
    $migration_label = $this->pluginManagerMigration->getDefinition($migration_id)['label'];
    return $this->t('Import @label', ['@label' => $migration_label]);
  }

  /**
   * Checks access for a specific CSV importer.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, string $migration_id) {

    // Delegate to the farm_import_csv.access service.
    if ($this->pluginManagerMigration->hasDefinition($migration_id)) {
      return $this->migrationAccess->access($account, $migration_id);
    }

    // Raise 404 if the migration does not exist.
    throw new ResourceNotFoundException();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $migration_id = NULL) {
    $form = parent::buildForm($form, $form_state);

    // Hard-code and hide the dropdown of migrations.
    $form['migrations']['#type'] = 'value';
    $form['migrations']['#value'] = $migration_id;

    // Remove the option to update existing records.
    // @todo https://www.drupal.org/project/farm/issues/2968909
    $form['update_existing_records']['#type'] = 'value';
    $form['update_existing_records']['#value'] = FALSE;

    // Rename the submit button to "Import" and set the weight to 100.
    $form['import']['#value'] = $this->t('Import');
    $form['import']['#weight'] = 100;

    // Load the migration definition.
    $migration = $this->pluginManagerMigration->getDefinition($migration_id);

    // Show column descriptions, if available.
    if (!empty($migration['third_party_settings']['farm_import_csv']['columns'])) {

      // Create a collapsed fieldset.
      $form['columns'] = [
        '#type' => 'details',
        '#title' => $this->t('CSV Columns'),
      ];

      // Show a description of the columns with a link to download a template.
      $items = [];
      foreach ($migration['third_party_settings']['farm_import_csv']['columns'] as $info) {
        if (!empty($info['name'])) {
          $item = '<strong>' . $info['name'] . '</strong>';
          if (!empty($info['description'])) {
            $item .= ': ' . $this->t($info['description']);
          }
          $items[] = Markup::create($item);
        }
      }
      $template_link = Link::createFromRoute($this->t('Download template'), 'farm.import.csv.template', ['migration_id' => $migration_id]);
      $form['columns']['descriptions'] = [
        '#theme' => 'item_list',
        '#items' => $items,
        '#suffix' => '<p>' . $template_link->toString() . '</p>',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // If there is no uploaded file, bail.
    if (empty($form_state->getValue('file_path'))) {
      return;
    }

    // Prepare the private://csv directory.
    $directory = 'private://csv';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Move the file to the private filesystem and register usage.
    /** @var \Drupal\file\FileStorageInterface $file_storage */
    $file_storage = $this->entityTypeManager->getStorage('file');
    /** @var \Drupal\file\FileInterface[] $files */
    $files = $file_storage->loadByProperties(['uri' => $form_state->getValue('file_path')]);
    if (!empty($files)) {
      $file = reset($files);
      $file = $this->fileRepository->move($file, $directory);
      $form_state->setValue('file_path', $file->getFileUri());
      $this->fileUsage->add($file, 'farm_import_csv', 'migration', $form_state->getValue('migrations'));
    }

    // Save the file ID to the private tempstore.
    $this->tempStore->set($this->currentUser()->id() . ':' . $form_state->getValue('migrations'), $file->id());
  }

  /**
   * Download a template for a CSV migration.
   *
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   An application/csv file download response object.
   */
  public function template(string $migration_id) {
    /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
    $migration = $this->pluginManagerMigration->getDefinition($migration_id);
    if (empty($migration) || $migration['migration_group'] != 'farm_import_csv') {
      throw new ResourceNotFoundException();
    }
    else {
      $filename = str_replace(':', '--', $migration_id) . '.csv';
      $response = new Response();
      $response->headers->set('Content-Type', 'application/csv');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
      $column_names = [];
      if (!empty($migration['third_party_settings']['farm_import_csv']['columns'])) {
        foreach ($migration['third_party_settings']['farm_import_csv']['columns'] as $column) {
          if (!empty($column['name'])) {
            $column_names[] = $column['name'];
          }
        }
      }
      $response->setContent(implode(',', $column_names));
      return $response;
    }
  }

}
