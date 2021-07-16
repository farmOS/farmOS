<?php

namespace Drupal\farm_quick_test\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\QuickFormBase;

/**
 * Test quick form.
 *
 * @QuickForm(
 *   id = "test",
 *   label = @Translation("Test quick form"),
 *   description = @Translation("Test quick form description."),
 *   helpText = @Translation("Test quick form help text."),
 *   permissions = {
 *     "create test log",
 *   }
 * )
 */
class Test extends QuickFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Test field.
    $form['test'] = [
      '#type' => 'number',
      '#title' => $this->t('Test field'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
