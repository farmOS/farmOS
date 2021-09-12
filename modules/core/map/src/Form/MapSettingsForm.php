<?php

namespace Drupal\farm_map\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a farm_map settings form.
 */
class MapSettingsForm extends ConfigFormbase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'farm_map.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_map_settings';
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

    // Add the enable side panel option.
    $form['enable_side_panel'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Side Panel'),
      '#description' => $this->t('Enable the side panel in farmOS maps for displaying additional settings and information.'),
      '#default_value' => $config->get('enable_side_panel'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('enable_side_panel', $form_state->getValue('enable_side_panel'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
