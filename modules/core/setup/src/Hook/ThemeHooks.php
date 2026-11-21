<?php

declare(strict_types=1);

namespace Drupal\farm_setup\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Theme hook implementations for farm_setup.
 */
class ThemeHooks {

  /**
   * Implements hook_farm_dashboard_panes().
   */
  #[Hook('farm_dashboard_panes')]
  public function farmDashboardPanes() {
    return [
      'setup' => [
        'block' => 'farm_setup',
        'region' => 'top',
        'weight' => -100,
      ],
    ];
  }

}
