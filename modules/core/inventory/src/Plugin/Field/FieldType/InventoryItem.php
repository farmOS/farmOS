<?php

namespace Drupal\farm_inventory\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'Inventory' field type.
 *
 * @FieldType(
 *   id = "inventory",
 *   label = @Translation("Inventory"),
 *   description = @Translation("This field stores asset inventory information."),
 *   category = @Translation("farmOS"),
 *   no_ui = TRUE,
 * )
 */
class InventoryItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'measure' => [
          'description' => 'Measure of the inventory',
          'type' => 'varchar',
          'length' => 32,
        ],
        'value' => [
          'description' => 'Value of the inventory',
          'type' => 'varchar',
          'length' => 32,
        ],
        'units' => [
          'description' => 'Units of the inventory',
          'type' => 'varchar',
          'length' => 128,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['measure'] = DataDefinition::create('string')
      ->setLabel(t('Measure of the inventory'));
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Value of the inventory'));
    $properties['units'] = DataDefinition::create('string')
      ->setLabel(t('Units of the inventory'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $measure = $this->get('measure');
    $value = $this->get('value');
    $units = $this->get('units');
    return ($measure === NULL || $measure->getValue() === '') && ($value === NULL || $value->getValue() === '') && ($units === NULL || $units->getValue() === '');
  }

}
