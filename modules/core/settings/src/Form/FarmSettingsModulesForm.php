<?php

namespace Drupal\farm_settings\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\farm\Form\FarmModulesForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for installing farmOS modules.
 *
 * @ingroup farm
 */
class FarmSettingsModulesForm extends FarmModulesForm {

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_settings_modules_form';
  }

  /**
   * Constructs a new FarmModulesForm.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state keyvalue collection to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   Module handler service.
   */
  public function __construct(StateInterface $state, ModuleHandlerInterface $module_handler) {
    parent::__construct($state);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function moduleOptions() {

    // Load the list of available modules.
    $modules = farm_modules();

    // Allow user to choose which high-level farm modules to install.
    $module_options = array_merge($modules['default'], $modules['optional']);

    // Check for enabled modules.
    $enabled_modules = [];
    foreach (array_keys($module_options) as $name) {
      if ($this->moduleHandler->moduleExists($name)) {
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

    // Assemble the batch operation for installing modules.
    $operations = [];
    foreach ($modules as $module => $weight) {
      $operations[] = [
        [__NAMESPACE__ . '\FarmSettingsModulesForm', 'farmInstallModuleBatch'],
        [$module, $this->moduleHandler->getName($module)],
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
