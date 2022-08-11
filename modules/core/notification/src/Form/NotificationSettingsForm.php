<?php

namespace Drupal\farm_notification\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a farm_notification settings form.
 */
class NotificationSettingsForm extends Formbase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_noication_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateinterface $form_state) {

    // Add a placeholder message.
    $form['placeholder'] = [
      '#type' => 'markup',
      '#markup' => $this->t('Select the tab for the type of notification to configure. Notification modules can add additional tabs to this page.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
