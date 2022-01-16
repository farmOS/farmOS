<?php

namespace Drupal\data_stream\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

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
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\entity\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\data_stream\Form\DataStreamForm",
 *       "edit" = "Drupal\data_stream\Form\DataStreamForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "local_task_provider" = {
 *       "default" = "\Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
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
 *   bundle_plugin_type = "data_stream_type",
 *   field_ui_base_route = "entity.data_stream_type.edit_form",
 *   common_reference_target = TRUE,
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "/data_stream/{data_stream}",
 *     "add-page" = "/data_stream/add",
 *     "add-form" = "/data_stream/add/{data_stream_type}",
 *     "delete-form" = "/data_stream/{data_stream}/delete",
 *     "delete-multiple-form" = "/data_stream/delete",
 *     "edit-form" = "/data_stream/{data_stream}/edit",
 *   },
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
    /** @var \Drupal\data_stream\Plugin\DataStream\DataStreamType\DataStreamTypeInterface $plugin */
    return \Drupal::service('plugin.manager.data_stream_type')->createInstance($this->bundle());
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
  public function getBundleLabel() {
    /** @var \Drupal\data_stream\Entity\DataStreamTypeInterface $type */
    $type = \Drupal::entityTypeManager()
      ->getStorage('data_stream_type')
      ->load($this->bundle());
    return $type->label();
  }

  /**
   * {@inheritdoc}
   */
  public static function getRequestTime() {
    return \Drupal::time()->getRequestTime();
  }

  /**
   * Create a unique key.
   *
   * @return string
   *   A new unique key.
   */
  public static function createUniqueKey() {
    return hash('md5', mt_rand());
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['private_key'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Private Key'))
      ->setDescription(t('Private key for the data stream.'))
      ->setTranslatable(TRUE)
      ->setDefaultValueCallback(static::class . '::createUniqueKey')
      ->setSetting('max_length', 255)
      ->setSetting('text_processing', 0)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['public'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Public'))
      ->setDescription(t('If the data stream has public access via API.'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'boolean',
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['asset'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Asset'))
      ->setDescription(t('Associate this data stream with any assets it describes.'))
      ->setTranslatable(FALSE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('target_type', 'asset')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference',
        'weight' => 12,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created on'))
      ->setDescription(t('The time that the data_stream was created.'))
      ->setRevisionable(TRUE)
      ->setDefaultValueCallback(static::class . '::getRequestTime')
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time the data_stream was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

}
