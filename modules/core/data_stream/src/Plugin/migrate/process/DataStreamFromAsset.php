<?php

namespace Drupal\data_stream\Plugin\migrate\process;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Gets the first data stream associated with an asset.
 *
 * This is helpful in migrating data that was previously associated with
 * a sensor asset ID.
 *
 * @MigrateProcessPlugin(
 *   id = "data_stream_from_asset"
 * )
 */
class DataStreamFromAsset extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a DataStreamFromAsset object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Get the asset_id.
    $asset_id = $row->getDestinationProperty('asset_id');

    // Bail if no asset ids are provided.
    if (empty($asset_id)) {
      return NULL;
    }

    // Load asset.
    $asset = $this->entityTypeManager->getStorage('asset')->load($asset_id);

    // Return the first data stream ID if one exists.
    if (!empty($asset) && $asset->hasField('data_stream')) {
      $ids = array_column($asset->data_stream->getValue(), 'target_id');
      return reset($ids);
    }

    return NULL;
  }

}
