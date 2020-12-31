<?php

namespace Drupal\farm_ui_action\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Component\Utility\Unicode;

/**
 * Defines farmOS action links.
 */
class FarmActions extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Load available entity types.
    $entity_types = array_keys(\Drupal::entityTypeManager()->getDefinitions());

    // Define the farmOS entity types we care about.
    $farm_types = [
      'asset',
      'log',
      'plan',
    ];

    // Iterate through the farmOS entity types.
    foreach ($farm_types as $type) {

      // If the entity type does not exist, skip it.
      if (!in_array($type, $entity_types)) {
        continue;
      }

      // Generate a link to [entity-type]/add.
      $name = 'farm.add.' . $type;
      $this->derivatives[$name] = $base_plugin_definition;
      $this->derivatives[$name]['title'] = 'Add ' . Unicode::ucfirst($type);
      $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_page';

      // Add it to entity Views, if the farm_ui_views module is enabled.
      if (\Drupal::moduleHandler()->moduleExists('farm_ui_views')) {
        $this->derivatives[$name]['appears_on'][] = 'view.farm_' . $type . '.page';

        // If this is a log, also add it to view.farm_log.page_user.
        if ($type == 'log') {
          $this->derivatives[$name]['appears_on'][] = 'view.farm_log.page_user';
        }
      }

      // Generate a link to [entity-type]/add/[bundle].
      $name = 'farm.add.' . $type . '.bundle';
      $this->derivatives[$name] = $base_plugin_definition;
      $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_form';
      $this->derivatives[$name]['class'] = 'Drupal\farm_ui_action\Plugin\Menu\LocalAction\AddEntity';
      $this->derivatives[$name]['entity_type'] = $type;

      // Add it to entity bundle Views, if the farm_ui_views module is enabled.
      if (\Drupal::moduleHandler()->moduleExists('farm_ui_views')) {
        $this->derivatives[$name]['appears_on'][] = 'view.farm_' . $type . '.page_type';
      }
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
