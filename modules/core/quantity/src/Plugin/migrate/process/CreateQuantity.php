<?php

namespace Drupal\quantity\Plugin\migrate\process;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create quantity entities.
 *
 * This is an alternative to using the entity_generate process plugin which
 * requires a "lookup" to happen before creating the entity. Since the quantity
 * value field is a Fraction field, it is easier to use our own process plugin.
 *
 * @MigrateProcessPlugin(
 *   id = "create_quantity",
 *   handle_multiples = TRUE
 * )
 *
 * @internal
 */
class CreateQuantity extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Quantity entity storage.
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
    $instance->quantityStorage = $container->get('entity_type.manager')->getStorage('quantity');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $return = [];

    if (is_array($value) || $value instanceof \Traversable) {
      foreach ($value as $i => $new_value) {

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
            $new_row = new Row($new_value);
            $source_value = $new_row->get($property) ?? $row->get($property);
            NestedArray::setValue($entity_values, explode(Row::PROPERTY_SEPARATOR, $key), $source_value, TRUE);
          }
        }

        // Create the entity.
        /** @var \Drupal\quantity\Entity\QuantityInterface $entity */
        $entity = $this->quantityStorage->create($entity_values);

        // Validate the entity.
        /** @var \Symfony\Component\Validator\ConstraintViolationInterface[] $violations */
        $violations = $entity->validate();
        if (!empty($violations)) {
          foreach ($violations as $violation) {
            throw new MigrateSkipRowException($violation->getPropertyPath() . '=' . $violation->getMessage());
          }
        }

        // Save the entity so it has an ID.
        $entity->save();

        // Add entity to the return array.
        $return[$i] = $entity;
      }
    }

    return $return;
  }

}
