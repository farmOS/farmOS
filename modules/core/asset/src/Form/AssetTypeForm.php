<?php

namespace Drupal\asset\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\state_machine\WorkflowManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for asset type entities.
 *
 * @package Drupal\asset\Form
 */
class AssetTypeForm extends EntityForm {

  /**
   * The workflow manager.
   *
   * @var \Drupal\state_machine\WorkflowManagerInterface
   */
  protected $workflowManager;

  /**
   * Constructs a new AssetTypeForm object.
   *
   * @param \Drupal\state_machine\WorkflowManagerInterface $workflow_manager
   *   The workflow manager.
   */
  public function __construct(WorkflowManagerInterface $workflow_manager) {
    $this->workflowManager = $workflow_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.workflow')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $asset_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $asset_type->label(),
      '#description' => $this->t('Label for the asset type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $asset_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\asset\Entity\AssetType::load',
      ],
      '#disabled' => !$asset_type->isNew(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $asset_type->getDescription(),
    ];

    $form['workflow'] = [
      '#type' => 'select',
      '#title' => $this->t('Workflow'),
      '#options' => $this->workflowManager->getGroupedLabels('asset'),
      '#default_value' => $asset_type->getWorkflowId(),
      '#description' => $this->t('Used by all assets of this type.'),
    ];

    $form['new_revision'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create new revision'),
      '#default_value' => $asset_type->shouldCreateNewRevision(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $asset_type = $this->entity;
    $status = $asset_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label asset type.', [
          '%label' => $asset_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label asset type.', [
          '%label' => $asset_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($asset_type->toUrl('collection'));

    return $status;
  }

}
