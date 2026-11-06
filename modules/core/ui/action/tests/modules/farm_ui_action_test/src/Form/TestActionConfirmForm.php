<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action_test\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a test action confirmation form.
 */
class TestActionConfirmForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('<front>');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
