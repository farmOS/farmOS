<?php

namespace Drupal\Tests\farm_quick\Kernel;

use Drupal\farm_quick\Traits\QuickStringTrait;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for quick string trait methods.
 *
 * @group farm
 */
class QuickStringTest extends KernelTestBase {

  use QuickStringTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_quick',
  ];

  /**
   * Test trimString() method.
   */
  public function testTrimString() {

    // Test that a 255 character string is not trimmed.
    $long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non';
    $name = $this->trimString($long_string, 255);
    $this->assertEquals($long_string, $name);

    // Test that a 256 character string is trimmed on a word boundary.
    $extra_long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non!';
    $trimmed_extra_long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia…';
    $name = $this->trimString($extra_long_string, 255);
    $this->assertEquals($trimmed_extra_long_string, $name);
  }

}
