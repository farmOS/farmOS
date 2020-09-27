<?php

namespace Drupal\farm_asset\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\entity\Revision\RevisionableContentEntityBase;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the farm_asset entity.
 *
 * @ingroup farm_asset
 *
 * @ContentEntityType(
 *   id = "farm_asset",
 *   label = @Translation("farm_asset"),
 *   bundle_label = @Translation("farm asset type"),
 *   label_collection = @Translation("farm assets"),
 *   label_singular = @Translation("farm asset"),
 *   label_plural = @Translation("farm assets"),
 *   label_count = @PluralTranslation(
 *     singular = "@count farm asset",
 *     plural = "@count farm assets",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\UncacheableEntityAccessControlHandler",
 *     "list_builder" = "\Drupal\farm_asset\FarmAssetListBuilder",
 *     "permission_provider" = "\Drupal\entity\UncacheableEntityPermissionProvider",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\farm_asset\Form\FarmAssetForm",
 *       "edit" = "Drupal\farm_asset\Form\FarmAssetForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\AdminHtmlRouteProvider",
 *       "revision" = "\Drupal\entity\Routing\RevisionRouteProvider",
 *       "delete-multiple" = "\Drupal\entity\Routing\DeleteMultipleRouteProvider",
 *     },
 *     "local_task_provider" = {
 *       "default" = "\Drupal\entity\Menu\DefaultEntityLocalTaskProvider",
 *     },
 *   },
 *   base_table = "farm_asset",
 *   data_table = "farm_asset_field_data",
 *   revision_table = "farm_asset_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer farm_assets",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "owner" = "uid",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   bundle_entity_type = "farm_asset_type",
 *   field_ui_base_route = "entity.farm_asset_type.edit_form",
 *   common_reference_target = TRUE,
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "/farm-asset/{farm_asset}",
 *     "add-page" = "/farm-asset/add",
 *     "add-form" = "/farm-asset/add/{farm_asset_type}",
 *     "collection" = "/admin/content/farm-asset",
 *     "delete-form" = "/farm-asset/{farm_asset}/delete",
 *     "delete-multiple-form" = "/farm-asset/delete",
 *     "edit-form" = "/farm-asset/{farm_asset}/edit",
 *     "revision" = "/farm-asset/{farm_asset}/revisions/{farm_asset_revision}/view",
 *     "revision-revert-form" = "/farm-asset/{farm_asset}/revisions/{farm_asset_revision}/revert",
 *     "version-history" = "/farm-asset/{farm_asset}/revisions",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message"
 *   },
 * )
 */
class FarmAsset extends RevisionableContentEntityBase implements FarmAssetInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getName();
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
  public function getTypeNamePattern() {
    /** @var \Drupal\farm_asset\Entity\FarmAssetTypeInterface $type */
    $type = \Drupal::entityTypeManager()
      ->getStorage('farm_asset_type')
      ->load($this->bundle());
    $name_pattern = $type->getNamePattern();
    return $name_pattern ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function getBundleLabel() {
    /** @var \Drupal\farm_asset\Entity\FarmAssetTypeInterface $type */
    $type = \Drupal::entityTypeManager()
      ->getStorage('farm_asset_type')
      ->load($this->bundle());
    return $type->label();
  }

  /**
   * {@inheritdoc}
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
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
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the asset. Leave this blank to automatically generate a name.'))
      ->setRevisionable(TRUE)
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

    $fields['status'] = BaseFieldDefinition::create('state')
      ->setLabel(t('Status'))
      ->setDescription(t('Indicates the status of the asset.'))
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'state_transition_form',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 11,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSetting('workflow_callback', ['\Drupal\farm_asset\Entity\FarmAsset', 'getWorkflowId']);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the asset.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\farm_asset\Entity\FarmAsset::getCurrentUserId')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 12,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the asset was created.'))
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
      ->setDescription(t('The time the farm_asset was last edited.'))
      ->setRevisionable(TRUE);

    return $fields;
  }

  /**
   * Gets the workflow ID for the state field.
   *
   * @param \Drupal\farm_asset\Entity\FarmAssetInterface $farm_asset
   *   The farm_asset entity.
   *
   * @return string
   *   The workflow ID.
   */
  public static function getWorkflowId(FarmAssetInterface $farm_asset) {
    $workflow = FarmAssetType::load($farm_asset->bundle())->getWorkflowId();
    return $workflow;
  }

}
