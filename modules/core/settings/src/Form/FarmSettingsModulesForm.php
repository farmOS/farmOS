<?php

namespace Drupal\farm_settings\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm\Form\FarmModulesForm;

/**
 * Form for installing farmOS modules.
 *
 * @ingroup farm
 */
class FarmSettingsModulesForm extends FarmModulesForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_settings_modules_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function moduleOptions() {

    // Load the list of available modules.
    $modules = farm_modules();

    // Allow user to choose which high-level farm modules to install.
    $module_options = array_merge($modules['default'], $modules['optional']);

    // Get a module handler.
    $module_handler = \Drupal::service('module_handler');

    // Check for enabled modules.
    $enabled_modules = [];
    foreach (array_keys($module_options) as $name) {
      if ($module_handler->moduleExists($name)) {
        $enabled_modules[] = $name;
      }
    }

    return [
      'options' => $module_options,
      'default' => $enabled_modules,
      'disabled' => $enabled_modules,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the list of modules that should be installed from form_state.
    $modules = array_filter($form_state->getValue('modules'));

    // Bail if no modules need to be installed.
    if (empty($modules)) {
      return;
    }

    // Load a list of all available modules, so that we can display their names.
    $files = \Drupal::service('extension.list.module')->getList();

    // Assemble the batch operation for installing modules.
    $operations = [];
    foreach ($modules as $module => $weight) {
      $operations[] = [
        [__NAMESPACE__ . '\FarmSettingsModulesForm', 'farmInstallModuleBatch'],
        [$module, $files[$module]->info['name']],
      ];
    }
    $batch = [
      'operations' => $operations,
      'title' => $this->t('Installing farmOS modules'),
      'error_message' => $this->t('The installation has encountered an error.'),
    ];

    batch_set($batch);
  }

  /**
   * Implements callback_batch_operation().
   *
   * Performs batch installation of farmOS modules.
   */
  public static function farmInstallModuleBatch($module, $module_name, &$context) {
    \Drupal::service('module_installer')->install([$module], TRUE);
    $context['results'][] = $module;
    $context['message'] = t('Installed %module module.', ['%module' => $module_name]);
  }

}
