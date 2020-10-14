<?php

namespace Drupal\farm_id_tag\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'ID tag' field type.
 *
 * @FieldType(
 *   id = "id_tag",
 *   label = @Translation("ID tag"),
 *   description = @Translation("This field stores a combination of id, tag type and location."),
 *   category = @Translation("farmOS"),
 *   default_widget = "id_tag",
 *   default_formatter = "id_tag"
 * )
 */
class IdTagItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'id' => [
          'description' => 'The id of the tag.',
          'type' => 'varchar',
          'length' => 8192,
          'not null' => TRUE,
        ],
        'type' => [
          'description' => 'The type of the tag.',
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'location' => [
          'description' => 'The location of the tag.',
          'type' => 'varchar',
          'length' => 1024,
          'not null' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['id'] = DataDefinition::create('string')
      ->setLabel(t('ID of the tag'));
    $properties['type'] = DataDefinition::create('string')
      ->setLabel(t('Type of the tag'));
    $properties['location'] = DataDefinition::create('string')
      ->setLabel(t('Location of the tag'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return ($this->id === NULL || $this->id === '') && ($this->type === NULL || $this->type === '') && ($this->location === NULL || $this->location === '');
  }

}
