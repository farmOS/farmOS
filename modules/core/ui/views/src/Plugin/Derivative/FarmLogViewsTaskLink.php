<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides task links for farmOS Logs Views.
 */
class FarmLogViewsTaskLink extends DeriverBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add links for each bundle.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo('log');
    foreach ($bundles as $type => $info) {
      $links['farm.asset.logs.' . $type] = [
        'title' => $info['label'],
        'parent_id' => 'farm.asset.logs',
        'route_name' => 'view.farm_log.page_asset',
        'route_parameters' => [
          'log_type' => $type,
        ],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
