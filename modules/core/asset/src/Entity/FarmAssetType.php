<?php

namespace Drupal\farm_asset\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the farm asset type entity.
 *
 * @ConfigEntityType(
 *   id = "farm_asset_type",
 *   label = @Translation("farm asset type"),
 *   label_collection = @Translation("farm asset types"),
 *   label_singular = @Translation("farm asset type"),
 *   label_plural = @Translation("farm asset types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count farm asset type",
 *     plural = "@count farm asset types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\farm_asset\FarmAssetTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\farm_asset\Form\FarmAssetTypeForm",
 *       "edit" = "Drupal\farm_asset\Form\FarmAssetTypeForm",
 *       "delete" = "\Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer farm_asset types",
 *   config_prefix = "type",
 *   bundle_of = "farm_asset",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/farm-asset-type/{farm_asset_type}",
 *     "add-form" = "/admin/structure/farm-asset-type/add",
 *     "edit-form" = "/admin/structure/farm-asset-type/{farm_asset_type}/edit",
 *     "delete-form" = "/admin/structure/farm-asset-type/{farm_asset_type}/delete",
 *     "collection" = "/admin/structure/farm-asset-type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "workflow",
 *     "new_revision",
 *   }
 * )
 */
class FarmAssetType extends ConfigEntityBundleBase implements FarmAssetTypeInterface {

  /**
   * The farm_asset type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The farm_asset type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this farm_asset type.
   *
   * @var string
   */
  protected $description;

  /**
   * The farm_asset type workflow ID.
   *
   * @var string
   */
  protected $workflow;

  /**
   * Default value of the 'Create new revision' checkbox of the farm_asset type.
   *
   * @var bool
   */
  protected $new_revision = TRUE;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    return $this->set('description', $description);
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // If the farm_asset type id changed, update all existing farm_assets of
    // that type.
    if ($update && $this->getOriginalId() != $this->id()) {
      $update_count = \Drupal::entityTypeManager()->getStorage('farm_asset')->updateType($this->getOriginalId(), $this->id());
      if ($update_count) {
        \Drupal::messenger()->addMessage(\Drupal::translation()->formatPlural($update_count,
          'Changed the farm_asset type of 1 post from %old-type to %type.',
          'Changed the farm_asset type of @count posts from %old-type to %type.',
          [
            '%old-type' => $this->getOriginalId(),
            '%type' => $this->id(),
          ]));
      }
    }
    if ($update) {
      // Clear the cached field definitions as some settings affect the field
      // definitions.
      \Drupal::entityTypeManager()->clearCachedDefinitions();
      \Drupal::service('entity_field.manager')->clearCachedFieldDefinitions();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getWorkflowId() {
    return $this->workflow;
  }

  /**
   * {@inheritdoc}
   */
  public function setWorkflowId($workflow_id) {
    $this->workflow = $workflow_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    parent::calculateDependencies();

    // The farm_asset type must depend on the module that provides the workflow.
    $workflow_manager = \Drupal::service('plugin.manager.workflow');
    $workflow = $workflow_manager->createInstance($this->getWorkflowId());
    $this->calculatePluginDependencies($workflow);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldCreateNewRevision() {
    return $this->new_revision;
  }

  /**
   * {@inheritdoc}
   */
  public function setNewRevision($new_revision) {
    return $this->set('new_revision', $new_revision);
  }

}
