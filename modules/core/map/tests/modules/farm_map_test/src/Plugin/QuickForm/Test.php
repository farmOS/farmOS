<?php

namespace Drupal\farm_map_test\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;

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

  use QuickLogTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Geometry field with a default value and no raw data textfield.
    $form['geometry1'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Geometry 1'),
      '#display_raw_geometry' => FALSE,
      '#default_value' => 'POINT(-42.689862437640826 32.621823310499934)',
    ];

    // Geometry field without a default value and a raw data field.
    $form['geometry2'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Geometry 2'),
      '#display_raw_geometry' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create two logs.
    $this->createLog([
      'type' => 'test',
      'name' => 'Test 1',
      'geometry' => $form_state->getValue('geometry1'),
    ]);
    $this->createLog([
      'type' => 'test',
      'name' => 'Test 2',
      'geometry' => $form_state->getValue('geometry2'),
    ]);
  }

}
