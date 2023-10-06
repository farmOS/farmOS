<?php

namespace Drupal\farm_quick_test\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\ConfigurableQuickFormInterface;
use Drupal\farm_quick\Traits\ConfigurableQuickFormTrait;

/**
 * Test configurable quick form.
 *
 * @QuickForm(
 *   id = "configurable_test",
 *   label = @Translation("Test configurable quick form"),
 *   description = @Translation("Test configurable quick form description."),
 *   helpText = @Translation("Test configurable quick form help text."),
 *   permissions = {
 *     "create test log",
 *   }
 * )
 */
class ConfigurableTest extends Test implements ConfigurableQuickFormInterface {

  use ConfigurableQuickFormTrait;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'test_default' => 100,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $form = parent::buildForm($form, $form_state, $id);

    // Set a default value from configuration.
    $form['test']['#default_value'] = $this->configuration['test_default'];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['test_default'] = [
      '#type' => 'number',
      '#title' => $this->t('Default value'),
      '#default_value' => $this->configuration['test_default'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['test_default'] = $form_state->getValue('test_default');
  }

}
