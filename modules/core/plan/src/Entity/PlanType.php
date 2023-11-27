<?php

namespace Drupal\plan\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the plan type entity.
 *
 * @ConfigEntityType(
 *   id = "plan_type",
 *   label = @Translation("Plan type"),
 *   label_collection = @Translation("Plan types"),
 *   label_singular = @Translation("Plan type"),
 *   label_plural = @Translation("Plan types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count plan type",
 *     plural = "@count plan types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\plan\PlanTypeListBuilder",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "form" = {
 *       "add" = "Drupal\plan\Form\PlanTypeForm",
 *       "edit" = "Drupal\plan\Form\PlanTypeForm",
 *       "delete" = "\Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer plan types",
 *   config_prefix = "type",
 *   bundle_of = "plan",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/plan-type/{plan_type}",
 *     "add-form" = "/admin/structure/plan-type/add",
 *     "edit-form" = "/admin/structure/plan-type/{plan_type}/edit",
 *     "delete-form" = "/admin/structure/plan-type/{plan_type}/delete",
 *     "collection" = "/admin/structure/plan-type"
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
class PlanType extends ConfigEntityBundleBase implements PlanTypeInterface {

  /**
   * The plan type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The plan type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this plan type.
   *
   * @var string
   */
  protected $description;

  /**
   * The plan type workflow ID.
   *
   * @var string
   */
  protected $workflow;

  /**
   * Default value of the 'Create new revision' checkbox of the plan type.
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

    // If the plan type id changed, update all existing plans of that type.
    if ($update && $this->getOriginalId() != $this->id()) {
      $update_count = \Drupal::entityTypeManager()->getStorage('plan')->updateType($this->getOriginalId(), $this->id());
      if ($update_count) {
        \Drupal::messenger()->addMessage(\Drupal::translation()->formatPlural($update_count,
          'Changed the plan type of 1 post from %old-type to %type.',
          'Changed the plan type of @count posts from %old-type to %type.',
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

    // The plan type must depend on the module that provides the workflow.
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
