<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_theme\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farmOS UI Theme module.
 *
 * @group farm
 */
class FarmUiThemeTest extends FarmBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_ui_theme',
    'farm_ui_theme_test',
  ];

  /**
   * Test that the "Powered by farmOS" block is visible.
   */
  public function testFarmBlock() {
    $this->assertSession()->pageTextContains('Powered by farmOS');
  }

}
