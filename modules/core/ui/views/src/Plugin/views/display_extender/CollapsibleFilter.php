<?php

namespace Drupal\farm_ui_views\Plugin\views\display_extender;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display_extender\DisplayExtenderPluginBase;

/**
 * Defines a display extender plugin to configure collapsible exposed filters.
 *
 * @ViewsDisplayExtender(
 *   id = "collapsible_filter",
 *   title = @Translation("Collapsible filter")
 * )
 */
class CollapsibleFilter extends DisplayExtenderPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['collapsible'] = ['default' => FALSE];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    switch ($form_state->get('section')) {
      case 'exposed_form_options':
        $form['collapsible'] = [
          '#title' => $this->t('Collapsible filter'),
          '#type' => 'checkbox',
          '#description' => $this->t('Display exposed filters in a collapsible details element.'),
          '#default_value' => $this->options['collapsible'],
        ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    switch ($form_state->get('section')) {
      case 'exposed_form_options':
        $this->options['collapsible'] = $form_state->getValue('collapsible');
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return ['farm_ui_views'];
  }

}
