<?php

namespace Drupal\farm_import_csv\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\file\FileRepositoryInterface;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\migrate\Plugin\MigrationPluginManager;
use Drupal\migrate_source_ui\Form\MigrateSourceUiForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   */
  public function __construct(MigrationPluginManager $plugin_manager_migration, ConfigFactoryInterface $config_factory, FileSystemInterface $file_system, FileRepositoryInterface $file_repository, FileUsageInterface $file_usage, EntityTypeManagerInterface $entity_type_manager, PrivateTempStoreFactory $temp_store_factory) {
    parent::__construct($plugin_manager_migration, $config_factory, $file_system);
    $this->fileRepository = $file_repository;
    $this->fileUsage = $file_usage;
    $this->entityTypeManager = $entity_type_manager;
    $this->tempStore = $temp_store_factory->get('farm_import_csv');
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_import_csv';
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

    // Rename the submit button to "Import".
    $form['import']['#value'] = $this->t('Import');

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

}
