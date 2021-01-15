<?php

namespace Drupal\Tests\farm\Functional;

/**
 * Tests integrated functionality of the farmOS profile.
 *
 * @group farm
 */
class FarmTest extends FarmBrowserTestBase {

  /**
   * Tests integrated functionality of the farmOS profile.
   */
  public function testFarm() {

    // Testing GH action. This should break.
    $this->assertEquals(0, 1);

    // Test that the profile was installed.
    $this->assertSame($this->profile, $this->container->getParameter('install_profile'));

    // Test that the "Powered by farmOS" block is visible.
    $this->assertText('Powered by farmOS');
  }

}
