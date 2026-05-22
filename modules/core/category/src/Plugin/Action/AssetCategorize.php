<?php

declare(strict_types=1);

namespace Drupal\farm_category\Plugin\Action;

use Drupal\Core\Action\Attribute\Action;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Action that assigns categories to assets.
 */
#[Action(
  id: 'asset_categorize_action',
  action_label: new TranslatableMarkup('Categorize asset'),
  confirm_form_route_name: 'farm_category.asset_categorize_action_form',
  type: 'asset',
)]
class AssetCategorize extends CategorizeBase {

}
