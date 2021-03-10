<?php

namespace Drupal\data_stream\Plugin\DataStream\DataStreamType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base data stream type class.
 */
abstract class DataStreamTypeBase extends PluginBase implements ContainerFactoryPluginInterface, DataStreamTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    // Get the plugin label.
    $label = $this->getLabel();

    // Render a fieldset for the plugin specific settings.
    $form[$this->getPluginId()] = [
      '#type'        => 'fieldset',
      '#title'       => $label,
      '#description' => $this->t('Settings for the %type data stream type.', ['%type' => $label]),
      '#weight'      => 10,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

}
