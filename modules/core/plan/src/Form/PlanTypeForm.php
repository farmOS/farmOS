<?php

namespace Drupal\plan\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\state_machine\WorkflowManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for plan type entities.
 *
 * @package Drupal\plan\Form
 */
class PlanTypeForm extends EntityForm {

  /**
   * The workflow manager.
   *
   * @var \Drupal\state_machine\WorkflowManagerInterface
   */
  protected $workflowManager;

  /**
   * Constructs a new PlanTypeForm object.
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
    $plan_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $plan_type->label(),
      '#description' => $this->t('Label for the plan type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $plan_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\plan\Entity\PlanType::load',
      ],
      '#disabled' => !$plan_type->isNew(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $plan_type->getDescription(),
    ];

    $form['workflow'] = [
      '#type' => 'select',
      '#title' => $this->t('Workflow'),
      '#options' => $this->workflowManager->getGroupedLabels('plan'),
      '#default_value' => $plan_type->getWorkflowId(),
      '#description' => $this->t('Used by all plans of this type.'),
    ];

    $form['new_revision'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create new revision'),
      '#default_value' => $plan_type->shouldCreateNewRevision(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $plan_type = $this->entity;
    $status = $plan_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label plan type.', [
          '%label' => $plan_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label plan type.', [
          '%label' => $plan_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($plan_type->toUrl('collection'));

    return $status;
  }

}
