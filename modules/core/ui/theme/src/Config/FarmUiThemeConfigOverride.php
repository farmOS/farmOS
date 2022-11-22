<?php

namespace Drupal\farm_ui_theme\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

/**
 * Config overrides from the farmOS UI Theme module.
 */
class FarmUiThemeConfigOverride implements ConfigFactoryOverrideInterface {

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = [];

    // Override the block.block.gin_local_actions config to use our class.
    $name = 'block.block.gin_local_actions';
    if (in_array($name, $names)) {
      $overrides[$name]['plugin'] = 'farm_local_actions_block';
      $overrides[$name]['settings']['id'] = 'farm_local_actions_block';
      $overrides[$name]['settings']['provider'] = 'farm_ui_theme';
    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'FarmUiThemeConfigOverride';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}
