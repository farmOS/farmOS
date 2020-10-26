<?php

namespace Drupal\data_stream;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for DataStream plugins.
 */
class DataStreamPluginBase extends PluginBase implements DataStreamPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    // Get the plugin label.
    $definition = $this->getPluginDefinition();
    $label = $definition['label'];

    // Render a fieldset for the plugin specific settings.
    $form[$this->getPluginId()] = [
      '#type' => 'fieldset',
      '#title' => $label,
      '#description' => $this->t('Settings for the %type data stream type.', ['%type' => $label]),
      '#weight' => 10,
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
