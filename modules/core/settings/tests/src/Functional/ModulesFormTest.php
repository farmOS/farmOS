<?php

namespace Drupal\Tests\farm_settings\Functional;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests installing modules via the module settings form.
 *
 * @group farm
 *
 * @see \Drupal\farm_settings\Form\FarmSettingsModulesForm
 */
class ModulesFormTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_settings',
    'farm_land',
    'farm_observation',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $GLOBALS['farm_test'] = TRUE;
    parent::setUp();

    // Login a user with administer farm settings permission.
    $user = $this->createUser(['administer farm settings']);
    $this->drupalLogin($user);
  }

  /**
   * Tests the install functionality of the module settings form.
   */
  public function testInstallFunctionality() {

    // Request the module settings page.
    $this->drupalGet('setup/modules');

    // Assert that installed modules are checked and disabled.
    foreach (['farm_land', 'farm_observation'] as $module) {
      $this->assertModuleCheckboxState('core', $module, TRUE, TRUE);
    };

    // Assert that uninstalled modules are unchecked.
    foreach (['farm_plant', 'farm_maintenance'] as $module) {
      $this->assertModuleCheckboxState('core', $module, FALSE, FALSE);
    }

    // Assert that the test module is uninstalled.
    $this->assertModuleCheckboxState('contrib', 'farm_settings_test', FALSE, FALSE);

    // Install the farm_plant and farm_settings_test modules.
    $this->installModules([
      'core' => ['farm_plant'],
      'contrib' => ['farm_settings_test'],
    ]);

    // Ensure farm_maintenance installed as farm_settings_test depends on it.
    $this->assertModuleCheckboxState('core', 'farm_maintenance', TRUE, TRUE);
  }

  /**
   * Helper function to assert the state of module checkboxes.
   *
   * @param string $type
   *   The module type. Core or contrib.
   * @param string $module
   *   The module name.
   * @param bool $checked
   *   Boolean if the checkbox should be checked. Defaults to FALSE.
   * @param bool $disabled
   *   Boolean if the checkbox should be disabled. Defaults to FALSE.
   */
  protected function assertModuleCheckboxState(string $type, string $module, bool $checked = FALSE, bool $disabled = FALSE) {
    $page = $this->getSession()->getPage();
    $field_name = $type . "[modules][$module]";
    $checkbox = $page->findField($field_name);
    $this->assertNotEmpty($checkbox, "The checkbox for $module exists.");

    $this->assertEquals($checked, $checkbox->isChecked(), "The $module checkbox is " . $checked ? '' : 'not ' . 'checked.');
    $this->assertEquals($disabled, $checkbox->hasAttribute('disabled'), "The $module checkbox is disabled: $disabled");
  }

  /**
   * Helper function to test installing a list of modules.
   *
   * @param array $modules
   *   The array of modules to install. Expects arrays of module names keyed
   *   by the module type.
   */
  protected function installModules(array $modules) {

    // Get the current page.
    $page = $this->getSession()->getPage();

    // Loop through module types, core or contrib.
    $this->assertNotEmpty($modules, 'Modules array is not empty.');
    foreach ($modules as $type => $module_list) {

      // Check each module in the list.
      $this->assertNotEmpty($module_list, 'Modules of the specified type are provided.');
      foreach ($module_list as $module_name) {
        $page->checkField($type . "[modules][$module_name]");
        $this->assertModuleCheckboxState($type, $module_name, TRUE, FALSE);
      }
    }

    // Submit the form.
    $page->pressButton('install-modules');

    // Wait for the batch process to complete.
    $this->assertSession()->waitForText('Install modules', 30000);

    // Rebuild the list of installed modules.
    $this->rebuildContainer();
    /** @var \Drupal\Core\Extension\ModuleExtensionList $module_list */
    $module_extension_list = \Drupal::service('extension.list.module');
    $module_extension_list->reset();

    // Assert that each module was installed and the form was updated.
    foreach ($modules as $type => $module_list) {
      foreach ($module_list as $module_name) {

        // This method raises an error if the module is not installed.
        $module_info = $module_extension_list->getExtensionInfo($module_name);
        $this->assertNotEmpty($module_info);

        // Assert that the checkbox is checked and disabled.
        $this->assertModuleCheckboxState($type, $module_name, TRUE, TRUE);
      }
    }
  }

}
