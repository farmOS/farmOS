<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Hook;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Action hook implementations for farm_ui_action.
 */
class ActionHooks {

  public function __construct(
    protected ModuleHandlerInterface $moduleHandler,
  ) {}

  /**
   * Implements hook_farm_exposed_entity_actions().
   */
  #[Hook('farm_exposed_entity_actions')]
  public function farmExposedEntityActions() {

    // Expose actions on behalf of farmOS core modules.
    return [

      // Asset actions.
      'quick_movement',
      'asset_add_log_action',
      'quick_group',
      'asset_farm_action',
      'asset_clone_action',
      'asset_csv_action',
      'asset_kml_action',

      // Log actions.
      // @todo Expose log_mark_as_done_action when it is hidden on done logs.
      // @see https://www.drupal.org/project/log/issues/3576009
      'log_reschedule_action',
      'log_clone_action',
      'log_csv_action',
      'log_quantity_csv_action',
      'log_kml_action',

      // Organization actions.
      'organization_clone_action',
    ];
  }

  /**
   * Implements hook_menu_local_actions_alter().
   */
  #[Hook('menu_local_actions_alter')]
  public function menuLocalActionsAlter(array &$local_actions) {
    $entity_types = [
      'asset',
      'log',
      'organization',
      'plan',
    ];
    foreach ($entity_types as $entity_type) {

      // Alter the "Add [entity_type]" action links, if available.
      // These are provided by their respective entity type modules.
      $action_key = 'entity.' . $entity_type . '.add_page';
      if (array_key_exists($action_key, $local_actions)) {

        // Show the action link on the farmOS dashboard, if available.
        if ($this->moduleHandler->moduleExists('farm_ui_dashboard')) {
          $local_actions[$action_key]['appears_on'][] = 'farm.dashboard';
        }

        // Show the "Add Log" action link on /user/%/logs, if available.
        if ($entity_type == 'log' && $this->moduleHandler->moduleExists('farm_ui_views')) {
          $local_actions[$action_key]['appears_on'][] = 'view.farm_log.page_user';
        }
      }
    }
  }

}
