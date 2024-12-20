<?php

namespace Drupal\plan\Plugin\FarmContext;

use Drupal\Core\StringTranslation\PluralTranslatableMarkup;
use Drupal\farm_ui_context\Plugin\FarmContext\FarmContextBase;
use Drupal\plan\Entity\PlanInterface;

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

      // Bail if no plans.
      $count = count($plans);
      if ($count === 0) {
        return $messages;
      }

      // Build a message summarizing the associated plans.
      $message = "Plans ($count)";
      $links = array_map(function (PlanInterface $plan) {
        return $plan->toLink()->toString();
      }, $plans);

      $long_message = new PluralTranslatableMarkup(
        $count,
        'This asset is associated with @count plan.',
        'This asset is associated with @count plans.',
      );
      $messages[] = [
        'type' => 'info',
        'message' => $message,
        'long_message' => $long_message,
        'links' => $links,
      ];
    }

    return $messages;
  }

}
