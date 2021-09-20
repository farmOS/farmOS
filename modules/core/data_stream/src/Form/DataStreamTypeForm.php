<?php

namespace Drupal\data_stream\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for data stream type entities.
 */
class DataStreamTypeForm extends EntityForm {

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
      '#description' => $this->t('Label for the data stream type.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $asset_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\data_stream\Entity\DataStreamType::load',
      ],
      '#disabled' => !$asset_type->isNew(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $asset_type->getDescription(),
    ];

    return $form;
  }

}
