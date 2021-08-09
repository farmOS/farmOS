<?php

namespace Drupal\farm_settings\Form;

use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for installing farmOS modules.
 *
 * @ingroup farm
 */
class FarmSettingsModulesForm extends FormBase {

  /**
   * The module extension list.
   *
   * @var \Drupal\Core\Extension\ModuleExtensionList
   */
  protected $moduleExtensionList;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_settings_modules_form';
  }

  /**
   * Constructs a new FarmSettingsModulesForm.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $module_extension_list
   *   The module extension list.
   */
  public function __construct(ModuleExtensionList $module_extension_list) {
    $this->moduleExtensionList = $module_extension_list;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.list.module'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Set the form title.
    $form['#title'] = $this->t('Enable modules');

    // Load module options.
    $modules = $this->moduleOptions();

    // Module checkboxes.
    $form['modules'] = [
      '#title' => $this->t('farmOS Modules'),
      '#title_display' => 'invisible',
      '#type' => 'checkboxes',
      '#description' => $this->t('Select the farmOS modules that you would like to be installed.'),
      '#options' => $modules['options'],
      '#default_value' => $modules['default'],
    ];

    // Disable checkboxes for modules marked as disabled.
    foreach ($modules['disabled'] as $name) {
      $form['modules'][$name]['#disabled'] = TRUE;
    }

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
  protected function moduleOptions() {

    // Load the list of available modules.
    $modules = farm_modules();

    // Allow user to choose which high-level farm modules to install.
    $module_options = array_merge($modules['default'], $modules['optional']);

    // Check and disable modules that are installed.
    $all_installed_modules = $this->moduleExtensionList->getAllInstalledInfo();
    $installed_modules = array_keys(array_intersect_key($module_options, $all_installed_modules));

    return [
      'options' => $module_options,
      'default' => $installed_modules,
      'disabled' => $installed_modules,
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

    // Assemble the batch operation for installing modules.
    $operations = [];
    foreach ($modules as $module => $weight) {
      $operations[] = [
        [__NAMESPACE__ . '\FarmSettingsModulesForm', 'farmInstallModuleBatch'],
        [$module, $this->moduleExtensionList->getName($module)],
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
