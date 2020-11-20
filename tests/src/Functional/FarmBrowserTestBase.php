<?php

namespace Drupal\Tests\farm\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Provides a base class for farmOS functional tests.
 */
class FarmBrowserTestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';
}
