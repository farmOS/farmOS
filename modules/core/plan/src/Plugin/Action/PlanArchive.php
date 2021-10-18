<?php

namespace Drupal\plan\Plugin\Action;

/**
 * Action that archives a plan.
 *
 * @Action(
 *   id = "plan_archive_action",
 *   label = @Translation("Archive a plan"),
 *   type = "plan"
 * )
 */
class PlanArchive extends PlanStateChangeBase {

  /**
   * {@inheritdoc}
   */
  protected $targetState = 'archived';

}
