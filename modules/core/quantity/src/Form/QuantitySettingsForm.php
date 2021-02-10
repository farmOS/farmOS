<?php

namespace Drupal\quantity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a quantity settings form.
 */
class QuantitySettingsForm extends ConfigFormbase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'quantity.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quantity_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateinterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['system_of_measurement'] = [
      '#type' => 'radios',
      '#title' => $this->t('System of measurement'),
      '#description' => $this->t('Select the system of measurement you would like to use in farmOS. Changing this setting after data has been entered is not recommended.'),
      '#options' => [
        'metric' => $this->t('Metric'),
        'us' => $this->t('US/Imperial'),
      ],
      '#default_value' => $config->get('system_of_measurement'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('system_of_measurement', $form_state->getValue('system_of_measurement'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
