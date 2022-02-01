<?php

namespace Drupal\plan\Plugin\FarmContext;

use Drupal\farm_ui_context\Plugin\FarmContext\FarmContextBase;

/**
 * Provides context when a log in a part of a plan.
 *
 * @todo Move to a module with a better dependency on the plan.log field.
 *
 * @FarmContext(
 *   id = "plan_log_farm_context",
 *   admin_label = @Translation("Plan log farm context"),
 *   context_definitions = {
 *     "log" = @ContextDefinition("entity:log", label = @Translation("Log")),
 *   },
 * )
 */
class PlanLogFarmContext extends FarmContextBase {

  /**
   * {@inheritdoc}
   */
  public function getMessages(): array {
    $messages = [];

    /** @var \Drupal\log\Entity\LogInterface $log */
    if ($log = $this->getContextValue('log')) {

      // Query for plans the log might be a part of.
      $plans = \Drupal::entityTypeManager()->getStorage('plan')->loadByProperties([
        'log' => $log->id(),
      ]);

      // Build a message for each plan the log is a part of.
      foreach ($plans as $plan) {
        $messages[] = [
          'type' => 'info',
          'message' => $this->t(
            'This log is a part of the plan: <a href="@plan_uri">@plan_label</a>',
            [
              '@plan_uri' => $plan->toUrl()->setAbsolute()->toString(),
              '@plan_label' => $plan->label(),
            ],
          ),
        ];
      }
    }

    return $messages;
  }

}
