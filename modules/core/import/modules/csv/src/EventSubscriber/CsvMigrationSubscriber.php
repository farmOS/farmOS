<?php

namespace Drupal\farm_import_csv\EventSubscriber;

use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribe to migration events.
 */
class CsvMigrationSubscriber implements EventSubscriberInterface {
  use StringTranslationTrait;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The tempstore service.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * CsvMigrationSubscriber constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore service.
   */
  public function __construct(Connection $database, AccountInterface $current_user, PrivateTempStoreFactory $temp_store_factory) {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->tempStore = $temp_store_factory->get('farm_import_csv');
  }

  /**
   * Get subscribed events.
   *
   * @inheritdoc
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::POST_IMPORT][] = ['onMigratePostImport'];
    return $events;
  }

  /**
   * Post-import logic.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The event object.
   */
  public function onMigratePostImport(MigrateImportEvent $event) {

    // If this is not a csv_file source migration, bail.
    if ($event->getMigration()->getSourcePlugin()->getPluginId() != 'csv_file') {
      return;
    }

    // Load the file ID from temporary storage (set during CSV upload form
    // submit), and show any messages associated with it.
    $tempstore_key = $this->currentUser->id() . ':' . $event->getMigration()->id();
    $file_id = $this->tempStore->get($tempstore_key);
    if (!is_null($file_id)) {

      // Query the migrate_map_* table, if it exists.
      // Migrate map tables are generated on-the-fly by the Drupal core migrate
      // module, only when needed. If no rows get imported (due to validation
      // errors, empty CSV files, etc), then the table will not be generated
      // when this code runs.
      $table = $event->getMigration()->getIdMap()->mapTableName();
      if ($this->database->schema()->tableExists($table)) {
        $query = $this->database->select($table, 'm');
        $query->addField('m', 'sourceid2');
        $query->condition('m.sourceid1', $file_id);
        $record_numbers = $query->execute()->fetchCol();
        foreach ($record_numbers as $record_number) {
          $messages = $event->getMigration()->getIdMap()->getMessages(['file_id' => $file_id, 'record_number' => $record_number]);
          foreach ($messages as $message) {
            $event->logMessage($this->t('Row @rownum: @message', ['@rownum' => $record_number, '@message' => $message->message]), 'warning');
          }
        }
      }
    }
  }

}
