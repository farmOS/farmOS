<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action_hook_test\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Action hook implementations for farm_ui_action_hook_test.
 */
class ActionHooks {

  /**
   * Implements hook_farm_exposed_entity_actions().
   */
  #[Hook('farm_exposed_entity_actions')]
  public function farmExposedEntityActions() {

    // Expose test actions.
    return [
      'test_action',
      'test_action_alter',
      'test_action_confirm',
      'test_action_create',
    ];
  }

  /**
   * Implements hook_farm_exposed_entity_actions_alter().
   */
  #[Hook('farm_exposed_entity_actions_alter')]
  public function farmExposedEntityActionsAlter(&$actions) {

    // Remove test_action_alter.
    if (($key = array_search('test_action_alter', $actions)) !== FALSE) {
      unset($actions[$key]);
    }
  }

}
