<?php

declare(strict_types=1);

namespace Drupal\farm_import_csv\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\StringTranslation\TranslationManager;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\farm_import_csv\StubMigrationMessage;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\MigrationPluginManager;
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
   * The file usage service.
   *
   * @var \Drupal\file\FileUsage\FileUsageInterface
   */
  protected $fileUsage;

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
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The File System service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $key_value
   *   The key value factory.
   * @param \Drupal\Core\StringTranslation\TranslationManager $translation_manager
   *   The translation manager service.
   * @param \Drupal\file\FileUsage\FileUsageInterface $file_usage
   *   The file usage service.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore service.
   */
  public function __construct(MigrationPluginManager $plugin_manager_migration, FileSystemInterface $file_system, TimeInterface $time, KeyValueFactoryInterface $key_value, TranslationManager $translation_manager, FileUsageInterface $file_usage, PrivateTempStoreFactory $temp_store_factory) {
    $this->migrationPluginManager = $plugin_manager_migration;
    $this->fileSystem = $file_system;
    $this->time = $time;
    $this->keyValueFactory = $key_value;
    $this->translationManager = $translation_manager;
    $this->fileUsage = $file_usage;
    $this->tempStore = $temp_store_factory->get('farm_import_csv');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('plugin.manager.migration'),
      $container->get('file_system'),
      $container->get('datetime.time'),
      $container->get('keyvalue'),
      $container->get('string_translation'),
      $container->get('file.usage'),
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

    // Migration ID.
    $form['migration_id'] = [
      '#type' => 'value',
      '#value' => $migration_id,
    ];

    // File upload field.
    $form['source_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Upload the source file'),
      '#upload_validators' => [
        'FileExtension' => [
          'extensions' => 'json csv xml',
        ],
      ],
    ];

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

    // Prepare the private://csv directory.
    $directory = 'private://csv';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    // Save the uploaded file.
    $validators = ['FileExtension' => ['extensions' => 'csv']];
    $file = file_save_upload('source_file', $validators, $directory, 0, FileExists::Replace);

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

    // Register file usage.
    $this->fileUsage->add($file, 'farm_import_csv', 'migration', $form_state->getValue('migration_id'));

    // Save the file ID to the private tempstore.
    $this->tempStore->set($this->currentUser()->id() . ':' . $form_state->getValue('migration_id'), $file->id());
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $migration_id = $form_state->getValue('migration_id');
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

}
