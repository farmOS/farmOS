<?php

namespace Drupal\farm_quantity\Plugin\migrate\process;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create farm_quantity entities.
 *
 * This is an alternative to using the entity_generate process plugin which
 * requires a "lookup" to happen before creating the entity. Since the quantity
 * value field is a Fraction field, it is easier to use our own process plugin.
 *
 * @MigrateProcessPlugin(
 *   id = "create_quantity"
 * )
 */
class CreateQuantity extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Farm quantity entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $quantityStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
    $instance->quantityStorage = $container->get('entity_type.manager')->getStorage('farm_quantity');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Start array of entity values.
    $entity_values = [];

    // Gather any static default values for properties/fields.
    if (isset($this->configuration['default_values']) && is_array($this->configuration['default_values'])) {
      foreach ($this->configuration['default_values'] as $key => $value) {
        $entity_values[$key] = $value;
      }
    }

    // Gather any additional properties/fields.
    if (isset($this->configuration['values']) && is_array($this->configuration['values'])) {
      foreach ($this->configuration['values'] as $key => $property) {
        $source_value = $row->get($property);
        NestedArray::setValue($entity_values, explode(Row::PROPERTY_SEPARATOR, $key), $source_value, TRUE);
      }
    }

    // Create the entity.
    $entity = $this->quantityStorage->create($entity_values);

    // Save the entity so it has an ID.
    $entity->save();

    // Return the ID.
    return $entity->id();
  }

}
