<?php

namespace Drupal\Tests\farm_test\Kernel;

use Drupal\asset\Entity\AssetInterface;

/**
 * Trait with helper functions for testing logic related to assets.
 */
trait FarmAssetTestTrait {

  /**
   * Helper function to assert the correct assets are included in an array.
   *
   * @param \Drupal\asset\Entity\AssetInterface[] $expected_assets
   *   The expected assets.
   * @param \Drupal\asset\Entity\AssetInterface[] $actual_assets
   *   The actual assets, optionally keyed by asset ID.
   * @param bool $check_keys
   *   If the actual asset keys should be checked. Defaults to FALSE.
   * @param string $message
   *   An optional message for the assert statement.
   */
  protected function assertCorrectAssets(array $expected_assets, array $actual_assets, bool $check_keys = FALSE, string $message = '') {

    // Message prefix.
    $prefix = empty($message) ? '' : "$message : ";

    // Test the asset count.
    $this->assertEquals(count($expected_assets), count($actual_assets), $prefix . 'Expected asset count does not match actual asset count.');

    // Test that expected assets are included in actual assets array.
    $actual_assets_keys = array_keys($actual_assets);
    $actual_assets_ids = array_map(function (AssetInterface $asset) {
      return $asset->id();
    }, $actual_assets);
    foreach ($expected_assets as $asset) {
      $this->assertTrue(in_array($asset->id(), $actual_assets_ids), $prefix . 'Expected asset is not included in the actual assets.');

      if ($check_keys) {
        $this->assertTrue(in_array($asset->id(), $actual_assets_keys), $prefix . 'Expected asset ID is not included in the actual assets array.');
      }
    }
  }

}
