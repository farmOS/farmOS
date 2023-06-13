<?php

namespace Drupal\farm_ui_views\Plugin\views\argument;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\taxonomy\Plugin\views\argument\Taxonomy;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Argument handler for taxonomy term references from an arbitrary entity field.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("entity_taxonomy_term_reference")
 */
class EntityTaxonomyTermReferenceArgument extends Taxonomy {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * The entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * EntityTaxonomyTermReferenceArgument constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $term_storage
   *   The taxonomy term storage service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity type bundle info service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager service.
   */
  public function __construct(array $configuration,
  $plugin_id,
  $plugin_definition,
  EntityStorageInterface $term_storage,
    EntityTypeManagerInterface $entity_type_manager,
  EntityTypeBundleInfoInterface $entity_bundle_info,
    EntityFieldManagerInterface $entity_field_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $term_storage);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityTypeBundleInfo = $entity_bundle_info;
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
      $container->get('entity_type.manager')->getStorage('taxonomy_term'),
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_field.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function query($group_by = FALSE) {
    // Getting the arguments through views rather than
    // from the Drupal route is important since it allows
    // the contextual filter previews in the views UI to
    // work correctly.
    $term_id = $this->argument;
    $entity_bundle = $this->view->args[1] ?: 'all';

    if (empty($term_id)) {
      return;
    }

    $term = $this->termStorage->load($term_id);

    // This is a value like 'asset' or 'log'.
    $base_entity_type = $this->view->getBaseEntityType()->id();

    $entity_storage = $this->entityTypeManager->getStorage($base_entity_type);

    if (!($entity_storage instanceof SqlContentEntityStorage)) {
      return;
    }

    $entity_data_table = $entity_storage->getDataTable();

    $entity_table_mapping = $entity_storage->getTableMapping();

    $conditions = [];

    // Keep track of which field tables we've already joined with since some
    // assets share the same field e.g. plant and seed assets.
    $already_joined_term_field_tables = [];

    // Loop through all the bundles of the base entity type for this view.
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($base_entity_type);
    foreach (array_keys($bundles) as $type) {
      // Consider either all of them or just the one matching the
      // bundle argument.
      if ($entity_bundle == 'all' || $type == $entity_bundle) {
        $field_definitions = $this->entityFieldManager->getFieldDefinitions($base_entity_type, $type);

        foreach ($field_definitions as $field_id => $field_definition) {
          // Look for taxonomy term entity reference fields which reference the
          // target bundle of the term we loaded above.
          if ($field_definition->getType() == "entity_reference" && $field_definition->getSetting('target_type') == "taxonomy_term") {
            $handler_settings = $field_definition->getSetting('handler_settings') ?? [];

            if (in_array($term->bundle(), $handler_settings['target_bundles'] ?? [])) {

              // Now that we have found such a field, get the parameters to
              // construct a join to allow us to filter only those entities
              // which actually reference the term we loaded above.
              $field_table_name = $entity_table_mapping->getFieldTableName($field_id);

              // Don't add the same join more than once.
              if (array_key_exists($field_table_name, $already_joined_term_field_tables)) {
                continue;
              }

              $column_names = $entity_table_mapping->getColumnNames($field_id);

              $target_id_column_name = $column_names['target_id'];

              // Join the taxonomy reference field table with the entity.
              /** @var \Drupal\views\Plugin\views\join\JoinPluginBase $join */
              $join = Views::pluginManager('join')->createInstance('standard', [
                'table' => $field_table_name,
                'field' => 'entity_id',
                'left_table' => $entity_data_table,
                'left_field' => 'id',
                'extra' => [
                  [
                    'field' => 'deleted',
                    'value' => 0,
                  ],
                  [
                    'field' => $target_id_column_name,
                    'value' => $term->id(),
                  ],
                ],
              ]);

              // Add the relationship.
              $relationship_alias = $this->query->addRelationship($field_table_name, $join, $entity_data_table);

              // Keep track that we've now joined with that field table.
              $already_joined_term_field_tables[$field_table_name] = 1;

              // Add a condition to our final WHERE statement that the joined
              // taxonomy term reference target id is not NULL.
              $conditions[] = "$relationship_alias.$target_id_column_name IS NOT NULL";
            }
          }
        }

      }
    }

    if (!empty($conditions)) {
      $combined_conditions = implode(" OR ", $conditions);

      $this->query->addWhereExpression(0, "$entity_data_table.id IS NOT NULL AND ($combined_conditions)");
    }
  }

}
