<?php

declare(strict_types=1);

namespace Drupal\farm_api_test\Plugin\Quantity\QuantityType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\QuantityType;
use Drupal\farm_entity\Plugin\Quantity\QuantityType\FarmQuantityType;

/**
 * Provides the test quantity type.
 */
#[QuantityType(
  id: 'test',
  label: new TranslatableMarkup('Test'),
)]
class TestQuantity extends FarmQuantityType {

}
