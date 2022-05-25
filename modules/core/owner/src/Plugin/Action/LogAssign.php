<?php

namespace Drupal\farm_owner\Plugin\Action;

/**
 * Action that assigns users to logs.
 *
 * @Action(
 *   id = "log_assign_action",
 *   label = @Translation("Assign users to logs."),
 *   type = "log",
 *   confirm_form_route_name = "farm_owner.log_assign_action_form"
 * )
 */
class LogAssign extends AssignBase {

}
