<?php

declare(strict_types=1);

namespace Drupal\farm_form\Traits;

/**
 * Provides a standard method for creating inline containers.
 */
trait FarmFormInlineContainerTrait {

  /**
   * Build an inline container element.
   *
   * @return array
   *   Returns a render array.
   */
  public function buildInlineContainer() {
    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'inline-container',
        ],
      ],
    ];
  }

}
