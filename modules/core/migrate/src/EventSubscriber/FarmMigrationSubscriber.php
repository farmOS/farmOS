<?php

namespace Drupal\farm_migrate\EventSubscriber;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Farm migration event subscriber.
 */
class FarmMigrationSubscriber implements EventSubscriberInterface {

  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The role storage.
   *
   * @var \Drupal\user\RoleStorageInterface
   */
  protected $roleStorage;

  /**
   * The state key/value store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * FarmMigrationSubscriber Constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state key/value store.
   */
  public function __construct(Connection $database, TimeInterface $time, EntityTypeManagerInterface $entity_type_manager, StateInterface $state) {
    $this->database = $database;
    $this->time = $time;
    $this->entityTypeManager = $entity_type_manager;
    $this->roleStorage = $entity_type_manager->getStorage('user_role');
    $this->state = $state;
  }

  /**
   * Get subscribed events.
   *
   * @inheritdoc
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::PRE_IMPORT][] = ['onMigratePreImport'];
    $events[MigrateEvents::POST_IMPORT][] = ['onMigratePostImport'];
    return $events;
  }

  /**
   * Run pre-migration logic.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function onMigratePreImport(MigrateImportEvent $event) {
    $this->grantTextFormatPermission($event);
    $this->allowPrivateFileReferencing($event);
  }

  /**
   * Run post-migration logic.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function onMigratePostImport(MigrateImportEvent $event) {
    $this->revokeTextFormatPermission($event);
    $this->preventPrivateFileReferencing($event);
    $this->addRevisionLogMessage($event);
  }

  /**
   * Grant default text format permission to anonymous role.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function grantTextFormatPermission(MigrateImportEvent $event) {

    // If the migration is in the farm_migrate_taxonomy migration group,
    // grant the 'use text format default' permission to anonymous role.
    // This allows entity validation to pass even when the migration is run
    // via Drush (which runs as the anonymous user). The permission is revoked
    // in post-migration.
    // @see revokeTextFormatPermission()
    $migration = $event->getMigration();
    if (isset($migration->migration_group) && $migration->migration_group == 'farm_migrate_taxonomy') {
      $anonymous = $this->roleStorage->load('anonymous');
      $anonymous->grantPermission('use text format default');
      $anonymous->save();
    }
  }

  /**
   * Revoke default text format permission from anonymous role.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function revokeTextFormatPermission(MigrateImportEvent $event) {

    // If the migration is in the farm_migrate_taxonomy migration group,
    // revoke the 'use text format default' permission to anonymous role.
    // This permission was added in pre-migration.
    // @see grantTextFormatPermission()
    $migration = $event->getMigration();
    if (isset($migration->migration_group) && $migration->migration_group == 'farm_migrate_taxonomy') {
      $anonymous = $this->roleStorage->load('anonymous');
      $anonymous->revokePermission('use text format default');
      $anonymous->save();
    }
  }

  /**
   * Temporarily allow private files to be referenced by entities.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function allowPrivateFileReferencing(MigrateImportEvent $event) {

    // During farmOS 1.x -> 2.x migrations, Drupal's FileAccessControlHandler
    // will not allow file entities to be referenced unless they were originally
    // uploaded by the same user that created the entity that references them.
    // In farmOS, it is common for an entity to be created by one user, and
    // photos to be uploaded to it later by a different user. With entity
    // validation enabled on the migration, this throws a validation error and
    // doesn't allow the file to be referenced.
    // We work around this by setting a Drupal state variable during our
    // migrations, and check for it in hook_ENTITY_TYPE_access(), so we can
    // explicitly grant access to the files.
    // This state is removed post-migration.
    // @see \Drupal\file\FileAccessControlHandler
    // @see farm_migrate_file_access()
    // @see preventPrivateFileReferencing()
    $migration_groups = [
      'farm_migrate_area',
      'farm_migrate_asset',
      'farm_migrate_log',
      'farm_migrate_plan',
      'farm_migrate_taxonomy',
    ];
    $migration = $event->getMigration();
    if (isset($migration->migration_group) && in_array($migration->migration_group, $migration_groups)) {
      $this->state->set('farm_migrate_allow_file_referencing', TRUE);
    }
  }

  /**
   * Prevent private files from being referenced by entities.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function preventPrivateFileReferencing(MigrateImportEvent $event) {

    // Unset the Drupal state variable that was set to temporarily allow private
    // files to be referenced by entities.
    // @see farm_migrate_file_access()
    // @see allowPrivateFileReferencing()
    $this->state->delete('farm_migrate_allow_file_referencing');
  }

  /**
   * Add a revision log message to imported entities.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function addRevisionLogMessage(MigrateImportEvent $event) {

    // Define the migration groups that we will post-process and their
    // corresponding entity revision tables.
    $groups_revision_tables = [
      'farm_migrate_asset' => 'asset_revision',
      'farm_migrate_area' => 'asset_revision',
      'farm_migrate_log' => 'log_revision',
      'farm_migrate_plan' => 'plan_revision',
      'farm_migrate_quantity' => 'quantity_revision',
      'farm_migrate_taxonomy' => 'taxonomy_term_revision',
    ];
    $migration = $event->getMigration();
    if (isset($migration->migration_group) && array_key_exists($migration->migration_group, $groups_revision_tables)) {

      // Define the entity id column name. This will be "id" in all cases
      // except taxonomy_terms, which use "tid".
      $id_column = 'id';
      if ($migration->migration_group == 'farm_migrate_taxonomy') {
        $id_column = 'tid';
      }

      // Build a query to set the revision log message.
      $revision_table = $groups_revision_tables[$migration->migration_group];
      $migration_id = $migration->id();
      $query = "UPDATE {$revision_table}
        SET revision_log_message = :revision_log_message
        WHERE revision_id IN (
          SELECT r.revision_id
          FROM {migrate_map_$migration_id} mm
          INNER JOIN {$revision_table} r ON mm.destid1 = r.$id_column
        )";
      $args = [
        ':revision_log_message' => 'Migrated from farmOS 1.x on ' . date('Y-m-d', $this->time->getRequestTime()),
      ];
      $this->database->query($query, $args);
    }
  }

}
