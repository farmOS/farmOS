<?php

declare(strict_types=1);

namespace Drupal\farm_api_test\Plugin\Plan\PlanType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\PlanType;
use Drupal\farm_entity\Plugin\Plan\PlanType\FarmPlanType;

/**
 * Provides the test plan type.
 */
#[PlanType(
  id: 'test',
  label: new TranslatableMarkup('Test'),
)]
class TestPlan extends FarmPlanType {

}
