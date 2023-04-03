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
   * The package name for farmOS quick form modules.
   *
   * @var string
   */
  const FARM_QUICK_PACKAGE = 'farmOS Quick Forms';

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
      '#title' => $this->t('Community modules'),
      '#open' => TRUE,
    ];

    // Quick form modules.
    $form['quick'] = [
      '#type' => 'details',
      '#title' => $this->t('Quick form modules'),
      '#open' => TRUE,
    ];

    // Submit button.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#name' => 'install-modules',
      '#value' => $this->t('Install modules'),
    ];

    // Load module options.
    $modules = $this->moduleOptions();

    // Build checkboxes for module options.
    foreach ($modules as $type => $options) {

      // Hide the fieldset if no modules are found.
      if (empty($options['options'])) {
        $form[$type]['#access'] = FALSE;
        continue;
      }

      // Build checkboxes.
      $form[$type]['modules'] = [
        '#title' => $this->t('farmOS Modules'),
        '#title_display' => 'invisible',
        '#type' => 'container',
        // form-checkboxes class is required so gin does not render each
        // checkbox as a toggle element.
        '#attributes' => [
          'class' => ['form-checkboxes'],
        ],
      ];

      // Add a checkbox for each module.
      foreach ($options['options'] as $module => $module_info) {
        $form[$type]['modules'][$module] = [
          '#type' => 'checkbox',
          '#title' => $module_info['name'],
          '#description' => $module_info['description'],
          '#default_value' => in_array($module, $options['default']),
        ];
      }

      // Disable checkboxes for modules marked as disabled.
      foreach ($options['disabled'] as $name) {
        $form[$type]['modules'][$name]['#disabled'] = TRUE;
      }
    }
    return $form;
  }

  /**
   * Helper function for building a list of modules to install.
   *
   * @return array
   *   Returns an array with two sub-arrays: `core` and `contrib`. Each of
   *   these includes three sub-arrays: 'options', 'default' and 'disabled'.
   *   All modules should be included in the 'options' array. Default modules
   *   will be selected for installation by default, and disabled modules
   *   cannot have their checkbox changed by users.
   */
  protected function moduleOptions() {

    // Reload the module list.
    $this->moduleExtensionList->reset();

    // Start an array of options for core and contrib modules.
    $options = [
      'core' => [],
      'contrib' => [],
      'quick' => [],
    ];

    // Build core module options.
    $modules = farm_modules();
    $core_modules = array_merge($modules['default'], $modules['optional']);

    // Load information about all modules.
    $all_module_info = $this->moduleExtensionList->getAllAvailableInfo();

    // Iterate through core modules and build options with name and description.
    foreach ($core_modules as $module => $module_name) {
      $options['core']['options'][$module] = [
        'name' => $all_module_info[$module]['name'],
        'description' => $all_module_info[$module]['description'] ?? NULL,
      ];
    }

    // Build contrib module options.
    $contrib_modules = array_filter($all_module_info, function ($module_info) {
      return isset($module_info['package']) && $module_info['package'] === static::FARM_CONTRIB_PACKAGE;
    });
    $options['contrib']['options'] = array_map(function ($module_info) {
      return [
        'name' => $module_info['name'],
        'description' => $module_info['description'] ?? NULL,
      ];
    }, $contrib_modules);

    // Build quick form module options.
    $quick_modules = array_filter($all_module_info, function ($module_info) {
      return isset($module_info['package']) && $module_info['package'] === static::FARM_QUICK_PACKAGE;
    });
    $options['quick']['options'] = array_map(function ($module_info) {
      return [
        'name' => $module_info['name'],
        'description' => $module_info['description'] ?? NULL,
      ];
    }, $quick_modules);

    // Check and disable modules that are installed.
    $all_installed_modules = $this->moduleExtensionList->getAllInstalledInfo();
    foreach (['core', 'contrib', 'quick'] as $option_key) {
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
    $quick_modules = array_filter($form_state->getValue(['quick', 'modules'], []));
    $selected_modules = array_merge($core_modules, $contrib_modules, $quick_modules);

    // Filter out installed modules.
    $all_installed_modules = $this->moduleExtensionList->getAllInstalledInfo();
    $to_install = array_diff_key($selected_modules, $all_installed_modules);

    // Bail if no modules need to be installed.
    if (empty($to_install)) {
      return;
    }

    // Assemble the batch operation for installing modules.
    $operations = [];
    foreach ($to_install as $module => $weight) {
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
