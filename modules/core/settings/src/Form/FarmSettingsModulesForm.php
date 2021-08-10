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
   * The package name for farmOS contrib modules.
   *
   * @var string
   */
  const FARM_CONTRIB_PACKAGE = 'farmOS Contrib';

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
    $form['#title'] = $this->t('Install modules');
    $form['#tree'] = TRUE;

    // Core modules.
    $form['core'] = [
      '#type' => 'details',
      '#title' => $this->t('Core modules'),
      '#open' => TRUE,
    ];

    // Contrib modules.
    $form['contrib'] = [
      '#type' => 'details',
      '#title' => $this->t('Contrib modules'),
      '#open' => TRUE,
    ];

    // Submit button.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Install modules'),
    ];

    // Load module options.
    $modules = $this->moduleOptions();

    // Build checkboxes for module options.
    foreach ($modules as $type => $options) {

      // Add message if there are no contrib modules.
      if (empty($options['options'])) {
        $form[$type]['modules'] = [
          '#markup' => $this->t('No @type farmOS modules found.', ['@type' => $type]),
        ];
        continue;
      }

      // Build checkboxes.
      $form[$type]['modules'] = [
        '#title' => $this->t('farmOS Modules'),
        '#title_display' => 'invisible',
        '#type' => 'checkboxes',
        '#description' => $this->t('Select the @type farmOS modules that you would like to be installed.', ['@type' => $type]),
        '#options' => $options['options'],
        '#default_value' => $options['default'],
      ];

      // Disable checkboxes for modules marked as disabled.
      foreach ($options['disabled'] as $name) {
        $form[$type]['modules'][$name]['#disabled'] = TRUE;
      }

      // Disable the submit button until an uninstalled module is checked.
      $uninstalled = array_diff(array_keys($options['options']), $options['default']);
      foreach ($uninstalled as $module_name) {
        $name = $type . "[modules][$module_name]";
        $form['actions']['submit']['#states']['disabled'][":input[name=\"$name\"]"] = ['checked' => FALSE];
      }
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function moduleOptions() {

    // Reload the module list.
    $this->moduleExtensionList->reset();

    // Start an array of options for core and contrib modules.
    $options = [
      'core' => [],
      'contrib' => [],
    ];

    // Build core module options.
    $modules = farm_modules();
    $options['core']['options'] = array_merge($modules['default'], $modules['optional']);

    // Build contrib module options.
    $contrib_modules = array_filter($this->moduleExtensionList->getAllAvailableInfo(), function ($module_info) {
      return isset($module_info['package']) && $module_info['package'] === static::FARM_CONTRIB_PACKAGE;
    });
    $options['contrib']['options'] = array_map(function ($module_info) {
      return $module_info['name'];
    }, $contrib_modules);

    // Check and disable modules that are installed.
    $all_installed_modules = $this->moduleExtensionList->getAllInstalledInfo();
    foreach (['core', 'contrib'] as $option_key) {
      $installed_modules = array_keys(array_intersect_key($options[$option_key]['options'], $all_installed_modules));
      $options[$option_key]['default'] = $installed_modules;
      $options[$option_key]['disabled'] = $installed_modules;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the list of modules that should be installed from form_state.
    $core_modules = array_filter($form_state->getValue(['core', 'modules'], []));
    $contrib_modules = array_filter($form_state->getValue(['contrib', 'modules'], []));
    $modules = array_merge($core_modules, $contrib_modules);

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
