<?php

namespace Drupal\plan\Plugin\FarmContext;

use Drupal\farm_ui_context\Plugin\FarmContext\FarmContextBase;

/**
 * Provides context when an asset in a part of a plan.
 *
 * @todo Move to a module with a better dependency on the plan.asset field.
 *
 * @FarmContext(
 *   id = "plan_asset_farm_context",
 *   admin_label = @Translation("Plan asset farm context"),
 *   context_definitions = {
 *     "asset" = @ContextDefinition("entity:asset", label = @Translation("Asset")),
 *   },
 * )
 */
class PlanAssetFarmContext extends FarmContextBase {

  /**
   * {@inheritdoc}
   */
  public function getMessages(): array {
    $messages = [];

    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    if ($asset = $this->getContextValue('asset')) {

      // Query for plans the asset might be a part of.
      $plans = \Drupal::entityTypeManager()->getStorage('plan')->loadByProperties([
        'asset' => $asset->id(),
      ]);

      // Build a message for each plan the asset is a part of.
      foreach ($plans as $plan) {
        $messages[] = [
          'type' => 'info',
          'message' => $this->t(
            'This asset is a part of the plan: <a href="@plan_uri">@plan_label</a>',
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
