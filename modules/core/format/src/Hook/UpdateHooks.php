<?php

declare(strict_types=1);

namespace Drupal\farm_format\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Update hook implementations for farm_format.
 */
class UpdateHooks {

  /**
   * Implements hook_farm_update_managed_config().
   */
  #[Hook('farm_update_managed_config')]
  public function farmUpdateManagedConfig() {
    return [

      // Declare the filter.format.default config as "managed" so it will be
      // automatically reverted if it is overridden.
      'filter.format.default',
    ];
  }

}
