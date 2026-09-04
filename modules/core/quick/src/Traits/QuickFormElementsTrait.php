<?php

declare(strict_types=1);

namespace Drupal\farm_quick\Traits;

use Drupal\farm_form\Traits\FarmFormInlineContainerTrait;

/**
 * Provides methods for building common quick form elements.
 *
 * @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
 *   Use FarmFormInlineContainerTrait instead.
 * @see https://www.drupal.org/node/3591770
 *
 * @phpstan-ignore trait.unused
 */
trait QuickFormElementsTrait {

  use FarmFormInlineContainerTrait;

}
