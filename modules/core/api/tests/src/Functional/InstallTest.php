<?php

namespace Drupal\Tests\farm_api\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests hook_install functionality of farm_api module.
 *
 * This must be implemented as a BrowserTest so that the module is fully
 * installed while testing. The KernelTestBase does not fully install modules.
 *
 * @group farm
 */
class InstallTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'simple_oauth',
    'farm_api',
  ];

  /**
   * Tests keys were created.
   */
  public function testKeys() {

    // Key directory.
    $dir_name = 'keys';

    // Save keys in the "keys" directory outside of the webroot.
    $relative_path = DRUPAL_ROOT . '/../' . $dir_name;
    $this->assertDirectoryExists($relative_path);

    $pub_filename = sprintf('%s/public.key', $relative_path);
    $pri_filename = sprintf('%s/private.key', $relative_path);

    $this->assertFileExists($pub_filename);
    $this->assertFileExists($pri_filename);
  }

}
