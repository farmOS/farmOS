<?php

namespace Drupal\farm_import_csv\Controller;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_import_csv\Access\CsvImportMigrationAccess;
use Drupal\migrate\Plugin\MigrationPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * CSV Import controller.
 */
class CsvImportController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManager
   */
  protected $pluginManagerMigration;

  /**
   * The CSV import migration access service.
   *
   * @var \Drupal\farm_import_csv\Access\CsvImportMigrationAccess
   */
  protected $migrationAccess;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new CsvImportController.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\migrate\Plugin\MigrationPluginManager $plugin_manager_migration
   *   The migration plugin manager.
   * @param \Drupal\farm_import_csv\Access\CsvImportMigrationAccess $migration_access
   *   The CSV import migration access service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree, FormBuilderInterface $form_builder, MigrationPluginManager $plugin_manager_migration, CsvImportMigrationAccess $migration_access, Connection $database) {
    $this->menuLinkTree = $menu_link_tree;
    $this->formBuilder = $form_builder;
    $this->pluginManagerMigration = $plugin_manager_migration;
    $this->migrationAccess = $migration_access;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('menu.link_tree'),
      $container->get('form_builder'),
      $container->get('plugin.manager.migration'),
      $container->get('farm_import_csv.access'),
      $container->get('database'),
    );
  }

  /**
   * The index of importers.
   *
   * @return array
   *   Returns a render array.
   */
  public function index(): array {

    // Load all menu links below it.
    $parameters = new MenuTreeParameters();
    $parameters->setRoot('farm.import.csv')->excludeRoot()->setTopLevelOnly()->onlyEnabledLinks();
    $tree = $this->menuLinkTree->load(NULL, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuLinkTree->transform($tree, $manipulators);

    // Start cacheability with migrate_plus migration config entity list tag.
    $tree_access_cacheability = new CacheableMetadata();
    $tree_access_cacheability->addCacheTags($this->entityTypeManager()->getStorage('migration')->getEntityType()->getListCacheTags());

    // Build items for each csv importer.
    $items = [];
    foreach ($tree as $element) {
      $tree_access_cacheability->addCacheableDependency($element->access);
      if ($element->access->isAllowed()) {
        $items[] = [
          'title' => $element->link->getTitle(),
          'description' => $element->link->getDescription(),
          'url' => $element->link->getUrlObject(),
        ];
      }
    }

    // Render items.
    if (!empty($items)) {
      $output = [
        '#theme' => 'admin_block_content',
        '#content' => $items,
      ];
    }
    else {
      $output = [
        '#markup' => $this->t('You do not have any importers.'),
      ];
    }
    $tree_access_cacheability->applyTo($output);
    return $output;
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
   * Get the title of a specific CSV importer.
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
   * Controller for individual importers.
   *
   * @param string $migration_id
   *   The migration ID.
   *
   * @return array
   *   Returns a render array.
   */
  public function importer(string $migration_id): array {

    // Load the migration definition.
    $migration = $this->pluginManagerMigration->getDefinition($migration_id);

    // Show column descriptions, if available.
    if (!empty($migration['third_party_settings']['farm_import_csv']['columns'])) {

      // Create a collapsed fieldset.
      $build['columns'] = [
        '#type' => 'details',
        '#title' => $this->t('CSV Columns'),
      ];

      // Show a description of the columns with a link to download a template.
      $items = [];
      foreach ($migration['third_party_settings']['farm_import_csv']['columns'] as $info) {
        if (!empty($info['name'])) {
          $item = '<strong>' . $info['name'] . '</strong>';
          if (!empty($info['description'])) {
            $item .= ': ' . $info['description'];
          }
          $items[] = Markup::create($item);
        }
      }
      $build['columns']['descriptions'] = [
        '#theme' => 'item_list',
        '#items' => $items,
      ];
      $template_link = Link::createFromRoute($this->t('Download template'), 'farm.import.csv.template', ['migration_id' => $migration_id]);
      $build['columns']['template'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $template_link->toString(),
      ];
    }

    // Add the importer form.
    $build['form'] = $this->formBuilder->getForm('Drupal\farm_import_csv\Form\CsvImportForm', $migration_id);

    // If entities have been created by this importer, display a View of them.
    if ($this->database->select('farm_import_csv_entity', 'e')->condition('e.migration', $migration_id)->countQuery()->execute()->fetchField()) {
      $entity_type = str_replace('entity:', '', $migration['destination']['plugin']);
      $build['imported'] = [
        '#type' => 'details',
        '#title' => $this->t('Imported records'),
        '#open' => TRUE,
        '#weight' => 100,
      ];
      $build['imported']['view'] = views_embed_view('farm_import_csv_' . $entity_type, 'default', $migration_id);
    }

    return $build;
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
    try {
      /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
      $migration = $this->pluginManagerMigration->getDefinition($migration_id);
    }
    catch (PluginNotFoundException $e) {
      throw new ResourceNotFoundException();
    }
    if ($migration['migration_group'] != 'farm_import_csv') {
      throw new ResourceNotFoundException();
    }
    else {
      $filename = str_replace(':', '--', $migration_id) . '.csv';
      $response = new Response();
      $response->headers->set('Content-Type', 'application/csv');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
      $column_names = array_filter(array_column($migration['third_party_settings']['farm_import_csv']['columns'] ?? [], 'name'));
      $response->setContent(implode(',', $column_names));
      return $response;
    }
  }

}
