<?php

namespace Drupal\farm_ui_menu\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides action links to create logs from the asset view.
 */
class FarmAssetAddLogActionLink extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add links for each log type.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo('log');
    foreach ($bundles as $type => $info) {
      $links['farm.asset.add_log.' . $type] = [
        'title' => 'Add ' . $info['label'],
        'route_name' => 'entity.log.add_form',
        'class' => '\Drupal\farm_ui_menu\Plugin\Menu\LocalAction\FarmAddLogPrepopulate',
        'appears_on' => [
          'entity.asset.canonical',
        ],
        'route_parameters' => ['log_type' => $type],
        'prepopulate' => [
          'asset' => [
            'route_parameter' => 'asset',
          ],
        ],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
