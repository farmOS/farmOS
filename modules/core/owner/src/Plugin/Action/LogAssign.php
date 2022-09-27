<?php

namespace Drupal\farm_owner\Plugin\Action;

/**
 * Action that assigns users to logs.
 *
 * @Action(
 *   id = "log_assign_action",
 *   label = @Translation("Assign logs to users."),
 *   type = "log",
 *   confirm_form_route_name = "farm_owner.log_assign_action_form"
 * )
 */
class LogAssign extends AssignBase {

}
