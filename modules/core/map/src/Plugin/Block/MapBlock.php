<?php

namespace Drupal\farm_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a map block.
 *
 * @Block(
 *   id = "map_block",
 *   admin_label = @Translation("Map block"),
 * )
 */
class MapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'map_type' => 'default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Map type.
    $form['map_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map type'),
      '#default_value' => $this->configuration['map_type'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Save map config values if no errors occurred.
    if (!$form_state->getErrors()) {
      $this->configuration['map_type'] = $form_state->getValue('map_type');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'farm_map',
      '#map_type' => $this->configuration['map_type'] ?? 'default',
    ];
  }

}
