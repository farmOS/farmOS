<?php

declare(strict_types=1);

namespace Drupal\farm_category\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Theme hook implementations for farm_category.
 */
class ThemeHooks {

  /**
   * Implements hook_farm_ui_theme_region_items().
   */
  #[Hook('farm_ui_theme_region_items')]
  public function farmUiThemeRegionItems(string $entity_type) {
    if ($entity_type == 'log') {
      return [
        'second' => [
          'category',
        ],
      ];
    }
    return [];
  }

}
