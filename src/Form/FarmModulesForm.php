<?php

namespace Drupal\farm\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for selecting farmOS modules to install.
 *
 * @ingroup farm
 */
class FarmModulesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_modules_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Set the form title.
    $form['#title'] = $this->t('Enable modules');

    // Load the list of available modules.
    $modules = farm_modules();

    // Allow user to choose which high-level farm modules to install.
    $module_options = array_merge($modules['default'], $modules['optional']);

    // Default modules will be selected by default.
    $module_defaults = array_keys($modules['default']);

    // Module checkboxes.
    $form['modules'] = [
      '#title' => $this->t('farmOS Modules'),
      '#title_display' => 'invisible',
      '#type' => 'checkboxes',
      '#description' => $this->t('Select the farmOS modules that you would like installed by default.'),
      '#options' => $module_options,
      '#default_value' => $module_defaults,
    ];

    // Submit button.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $modules = array_filter($form_state->getValue('modules'));
    \Drupal::state()->set('farm.install_modules', $modules);
  }

}
