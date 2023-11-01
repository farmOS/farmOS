<?php

namespace Drupal\farm_map_mapbox\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a mapbox settings form.
 */
class MapboxSettingsForm extends ConfigFormbase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'farm_map_mapbox.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_map_mapbox_settings';
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

    // Add api_key field.
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mapbox API Key'),
      '#description' => $this->t('Enter your Mapbox API key.'),
      '#default_value' => $config->get('api_key'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();
    drupal_flush_all_caches();

    parent::submitForm($form, $form_state);
  }

}
