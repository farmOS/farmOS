<?php

namespace Drupal\data_stream\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Data Stream entity.
 *
 * @ContentEntityType(
 *   id = "data_stream",
 *   label = @Translation("Data stream"),
 *   bundle_label = @Translation("Data stream type"),
 *   label_collection = @Translation("Data streams"),
 *   label_singular = @Translation("Data stream"),
 *   label_plural = @Translation("Data streams"),
 *   label_count = @PluralTranslation(
 *     singular = "@count data stream",
 *     plural = "@count data streams",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\UncacheableEntityAccessControlHandler",
 *     "permission_provider" = "\Drupal\entity\UncacheableEntityPermissionProvider",
 *   },
 *   base_table = "data_stream",
 *   data_table = "data_stream_data",
 *   translatable = TRUE,
 *   admin_permission = "administer data streams",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   bundle_entity_type = "data_stream_type",
 *   permission_granularity = "bundle",
 * )
 */
class DataStream extends ContentEntityBase implements DataStreamInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getName();
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin() {
    /** @var \Drupal\data_stream\Entity\DataStreamTypeInterface $plugin */
    $plugin = \Drupal::service('plugin.manager.data_stream')->createInstance($this->bundle());
    return $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrivateKey() {
    return $this->get('private_key')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublic() {
    return $this->get('public')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function getRequestTime() {
    return \Drupal::time()->getRequestTime();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the data stream.'))
      ->setTranslatable(TRUE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['private_key'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Private Key'))
      ->setDescription(t('Private key for the data stream.'))
      ->setTranslatable(TRUE)
      ->setDefaultValue('')
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['public'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Public'))
      ->setDescription(t('If the data stream has public access via API.'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -6,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setDescription(t('The time that the data_stream was created.'))
      ->setRevisionable(TRUE)
      ->setDefaultValueCallback(static::class . '::getRequestTime')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'timestamp',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 13,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time the data_stream was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}
