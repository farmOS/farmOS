<?php

declare(strict_types=1);

namespace Drupal\farm_ui_search\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Update hook implementations for farm_ui_search.
 */
class UpdateHooks {

  /**
   * Implements hook_farm_update_managed_config().
   */
  #[Hook('farm_update_managed_config')]
  public function farmUpdateManagedConfig() {
    return [
      'search_api.index.default',
      'search_api.server.default',
      'views.view.farm_search',
    ];
  }

}
