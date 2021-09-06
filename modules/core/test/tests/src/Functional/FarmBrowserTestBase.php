<?php

namespace Drupal\Tests\farm_test\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Provides a base class for farmOS functional tests.
 */
class FarmBrowserTestBase extends BrowserTestBase {

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
  protected function setUp(): void {

    // Set a global farm_test variable and then delegate to the parent setUp().
    // This is a temporary hack to prevent optional default farmOS modules from
    // being installed via the profile's hook_install_tasks().
    // @see farm_install_modules()
    // @todo https://www.drupal.org/project/farm/issues/3183739
    $GLOBALS['farm_test'] = TRUE;
    parent::setUp();
  }

}
