<?php

namespace Drupal\quantity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for quantity type entities.
 */
class QuantityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $quantity_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $quantity_type->label(),
      '#description' => $this->t('Label for the quantity type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $quantity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\quantity\Entity\QuantityType::load',
      ],
      '#disabled' => !$quantity_type->isNew(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $quantity_type->getDescription(),
    ];

    $form['new_revision'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create new revision'),
      '#default_value' => $quantity_type->shouldCreateNewRevision(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $quantity_type = $this->entity;
    $status = $quantity_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label quantity type.', [
          '%label' => $quantity_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label quantity type.', [
          '%label' => $quantity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($quantity_type->toUrl('collection'));
  }

}
