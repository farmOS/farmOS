<?php

namespace Drupal\Tests\farm_update\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for farmOS Update module.
 *
 * @group farm
 */
class FarmUpdateTest extends KernelTestBase {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The farm update service.
   *
   * @var \Drupal\farm_update\FarmUpdateInterface
   */
  protected $farmUpdate;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'config_update',
    'farm_update',
    'farm_update_test',
    'farm_flag',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->configFactory = \Drupal::service('config.factory');
    $this->farmUpdate = \Drupal::service('farm.update');
    $this->installEntitySchema('flag');
    $this->installConfig([
      'farm_update_test',
      'farm_flag',
    ]);
  }

  /**
   * Test farmOS Update module.
   */
  public function testFarmUpdate() {

    // Confirm that overridden config gets reverted.
    $this->farmUpdateTestRevertSetting('farm_flag.flag.monitor', 'label', 'Changed');

    // Confirm that config excluded via hook_farm_update_exclude_config() does
    // not get reverted.
    $this->farmUpdateTestRevertSetting('farm_flag.flag.priority', 'label', 'Changed', TRUE);

    // Confirm that config excluded via farm_update.settings does not get
    // reverted.
    $this->farmUpdateTestRevertSetting('farm_flag.flag.review', 'label', 'Changed', TRUE);
  }

  /**
   * Helper method to test reverting a setting.
   *
   * @param string $config
   *   Configuration name.
   * @param string $setting
   *   Setting name within the configuration.
   * @param string $override
   *   Value to use for override.
   * @param bool $excluded
   *   Whether or not we expect this config to be excluded. Defaults to FALSE.
   *   If set to TRUE, then we expect that the config will still be overridden
   *   after rebuild.
   */
  protected function farmUpdateTestRevertSetting(string $config, string $setting, string $override, bool $excluded = FALSE) {
    $original = \Drupal::config($config)->get($setting);
    $this->configFactory->getEditable($config)->set($setting, $override)->save();
    $this->assertEquals($override, \Drupal::config($config)->get($setting), 'Setting is overridden before rebuild.');
    $this->farmUpdate->rebuild();
    $expected_value = $excluded ? $override : $original;
    $expected_message = $excluded ? 'Setting is overridden after rebuild.' : 'Setting is reverted after rebuild.';
    $this->assertEquals($expected_value, \Drupal::config($config)->get($setting), $expected_message);
  }

}
