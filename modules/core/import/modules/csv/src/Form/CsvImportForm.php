<?php

declare(strict_types=1);

namespace Drupal\farm_import_csv\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\StringTranslation\TranslationManager;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\farm_import_csv\StubMigrationMessage;
use Drupal\file\FileRepositoryInterface;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\MigrationPluginManager;
use Drupal\migrate_plus\Plugin\migrate\source\Url;
use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Json;
use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Xml;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate_tools\MigrateBatchExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the CSV import form.
 */
class CsvImportForm extends FormBase {

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManager
   */
  protected MigrationPluginManager $migrationPluginManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected FileSystemInterface $fileSystem;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected TimeInterface $time;

  /**
   * The key-value factory.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface
   */
  protected KeyValueFactoryInterface $keyValueFactory;

  /**
   * The translation manager.
   *
   * @var \Drupal\Core\StringTranslation\TranslationManager
   */
  protected TranslationManager $translationManager;

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
   * The farm_import_csv temp store.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStore
   */
  protected $tempStore;

  /**
   * CsvImportForm constructor.
   *
   * @param \Drupal\migrate\Plugin\MigrationPluginManager $plugin_manager_migration
   *   The migration plugin manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The File System service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $key_value
   *   The key value factory.
   * @param \Drupal\Core\StringTranslation\TranslationManager $translation_manager
   *   The translation manager service.
   * @param \Drupal\file\FileRepositoryInterface $file_repository
   *   The file repository service.
   * @param \Drupal\file\FileUsage\FileUsageInterface $file_usage
   *   The file usage service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore service.
   */
  public function __construct(MigrationPluginManager $plugin_manager_migration, ConfigFactoryInterface $config_factory, FileSystemInterface $file_system, TimeInterface $time, KeyValueFactoryInterface $key_value, TranslationManager $translation_manager, FileRepositoryInterface $file_repository, FileUsageInterface $file_usage, EntityTypeManagerInterface $entity_type_manager, PrivateTempStoreFactory $temp_store_factory) {
    $this->migrationPluginManager = $plugin_manager_migration;
    $this->configFactory = $config_factory;
    $this->fileSystem = $file_system;
    $this->time = $time;
    $this->keyValueFactory = $key_value;
    $this->translationManager = $translation_manager;
    $this->fileRepository = $file_repository;
    $this->fileUsage = $file_usage;
    $this->entityTypeManager = $entity_type_manager;
    $this->tempStore = $temp_store_factory->get('farm_import_csv');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('plugin.manager.migration'),
      $container->get('config.factory'),
      $container->get('file_system'),
      $container->get('datetime.time'),
      $container->get('keyvalue'),
      $container->get('string_translation'),
      $container->get('file.repository'),
      $container->get('file.usage'),
      $container->get('entity_type.manager'),
      $container->get('tempstore.private'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'farm_import_csv';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $migration_id = NULL): array {

    // Original MigrateSourceUiForm::buildForm() code.
    $options = [];
    foreach ($this->migrationPluginManager->getDefinitions() as $definition) {
      if ($extension = $this->getFileExtensionSupported($definition)) {
        $options[$definition['id']] = $this->t('%id (supports %file_type)', [
          '%id' => $definition['label'] ?? $definition['id'],
          '%file_type' => $extension,
        ]);
      }
    }
    natcasesort($options);
    $form['migrations'] = [
      '#type' => 'select',
      '#title' => $this->t('Migrations'),
      '#options' => $options,
    ];
    $form['source_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload the source file'),
      '#upload_validators' => [
        'FileExtension' => [
          'extensions' => 'json csv xml',
        ],
      ],
    ];

    // Hard-code and hide the dropdown of migrations.
    $form['migrations']['#type'] = 'value';
    $form['migrations']['#value'] = $migration_id;

    // Import submit button.
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

    // Original MigrateSourceUiForm::validateForm() code.
    $migration_id = $form_state->getValue('migrations');
    $definition = $this->migrationPluginManager->getDefinition($migration_id);
    $extension = $this->getFileExtensionSupported($definition);

    $validators = ['FileExtension' => ['extensions' => $extension]];
    // Check to see if a specific file temp directory is configured. If not,
    // default the value to FALSE, which will instruct file_save_upload() to
    // use Drupal's temporary files scheme.
    $file_destination = $this->configFactory->get('migrate_source_ui.settings')->get('file_temp_directory');
    if (is_null($file_destination)) {
      $file_destination = FALSE;
    }

    $directory = $this->fileSystem->realpath($file_destination);
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    $file = file_save_upload('source_file', $validators, $file_destination, 0, FileExists::Replace);

    if (isset($file)) {
      // File upload was attempted.
      if ($file) {
        $form_state->setValue('file_path', $file->getFileUri());
      }
      // File upload failed.
      else {
        $form_state->setErrorByName('source_file', $this->t('The file could not be uploaded.'));
      }
    }
    else {
      $form_state->setErrorByName('source_file', $this->t('You have to upload a source file.'));
    }

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
    if (empty($files)) {
      return;
    }
    $file = reset($files);
    $file = $this->fileRepository->move($file, $directory);
    $form_state->setValue('file_path', $file->getFileUri());
    $this->fileUsage->add($file, 'farm_import_csv', 'migration', $form_state->getValue('migrations'));

    // Save the file ID to the private tempstore.
    $this->tempStore->set($this->currentUser()->id() . ':' . $form_state->getValue('migrations'), $file->id());
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $migration_id = $form_state->getValue('migrations');
    /** @var \Drupal\migrate\Plugin\Migration $migration */
    $migration = $this->migrationPluginManager->createInstance($migration_id);

    // Reset status.
    $status = $migration->getStatus();
    if ($status !== MigrationInterface::STATUS_IDLE) {
      $migration->setStatus(MigrationInterface::STATUS_IDLE);
      $this->messenger()->addWarning($this->t('Migration @id reset to Idle', ['@id' => $migration_id]));
    }

    // Build and execute the batch operation.
    $batch_options = [
      'configuration' => [
        'source' => [
          'path' => $form_state->getValue('file_path'),
        ],
      ],
    ];
    $executable = new MigrateBatchExecutable($migration, new StubMigrationMessage(), $this->keyValueFactory, $this->time, $this->translationManager, $this->migrationPluginManager, $batch_options);
    $executable->batchImport();
  }

  /**
   * The allowed file extension for the migration.
   *
   * @param array $definition
   *   The migration definition array.
   *
   * @return string|null
   *   The file extension or null if not detected.
   */
  public function getFileExtensionSupported(array $definition): ?string {
    $extension_detected = NULL;
    $extensions_allowed = ['csv', 'json', 'xml'];

    $migrationInstance = $this->migrationPluginManager->createStubMigration($definition);
    if ($migrationInstance->getSourcePlugin() instanceof CSV) {
      $extension_detected = 'csv';
    }
    elseif ($migrationInstance->getSourcePlugin() instanceof Json) {
      $extension_detected = 'json';
    }
    elseif ($migrationInstance->getSourcePlugin() instanceof Spreadsheet) {
      $extension_detected = 'csv ods slk xls xlsx xml';
    }
    elseif ($migrationInstance->getSourcePlugin() instanceof Xml) {
      $extension_detected = 'xml';
    }
    elseif ($migrationInstance->getSourcePlugin() instanceof Url) {
      $extension_detected = NestedArray::getValue($definition, [
        'source',
        'data_parser_plugin',
      ]);
      if ($extension_detected === 'simple_xml') {
        $extension_detected = 'xml';
      }
    }

    if ($extension_detected && in_array($extension_detected, $extensions_allowed, TRUE)) {
      return $extension_detected;
    }
    return NULL;
  }

}
