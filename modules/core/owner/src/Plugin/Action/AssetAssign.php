<?php

namespace Drupal\farm_owner\Plugin\Action;

/**
 * Action that assigns users to assets.
 *
 * @Action(
 *   id = "asset_assign_action",
 *   label = @Translation("Assign assets to users."),
 *   type = "asset",
 *   confirm_form_route_name = "farm_owner.asset_assign_action_form"
 * )
 */
class AssetAssign extends AssignBase {

}
