<?php

declare(strict_types=1);

namespace Drupal\farm_ui_search\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Theme hook implementations for farm_ui_search.
 */
class ThemeHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_farm_dashboard_panes().
   */
  #[Hook('farm_dashboard_panes')]
  public function farmDashboardPanes() {
    return [
      'search' => [
        'view' => 'farm_search',
        'view_display_id' => 'block',
        'region' => 'top',
        'weight' => -90,
      ],
    ];
  }

}
