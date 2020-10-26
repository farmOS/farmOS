<?php

namespace Drupal\data_stream\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for data stream entities.
 */
class DataStreamForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    // Build the parent form.
    $form = parent::form($form, $form_state);

    // Generate a new private key if creating a new data stream.
    if ($this->operation == 'add') {
      $new = hash('md5', mt_rand());
      $form['private_key']['widget'][0]['value']['#default_value'] = $new;
    }

    if ($plugin = $this->entity->getPlugin()) {
      $form += $plugin->buildConfigurationForm($form, $form_state);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if ($plugin = $this->entity->getPlugin()) {
      $plugin->validateConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    if ($plugin = $this->entity->getPlugin()) {
      $plugin->submitConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $this->messenger()->addMessage($this->t('Saved the %label data stream.', ['%label' => $this->entity->label()]));
    $account = $this->currentUser();
    if ($account->hasPermission('administer data streams')) {
      $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    }
    else {
      $form_state->setRedirectUrl($this->entity->toUrl());
    }
  }

}
