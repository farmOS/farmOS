<?php

namespace Drupal\farm_quick\Traits;

/**
 * Provides methods for building common quick form elements.
 */
trait QuickFormElementsTrait {

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
