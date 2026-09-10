<?php

declare(strict_types=1);

namespace Drupal\farm_quick\Traits;

use Drupal\asset\Entity\AssetInterface;

/**
 * Provides methods for building/processing common quick form elements.
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

  /**
   * Load assets from entity_autocomplete values.
   *
   * @param array|null $values
   *   The value from $form_state->getValue().
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   Returns an array of assets.
   */
  protected function loadEntityAutocompleteAssets($values) {
    $entities = [];
    if (empty($values)) {
      return $entities;
    }
    foreach ($values as $value) {
      if (is_array($value) && !empty($value['target_id'])) {
        $value = $this->entityTypeManager->getStorage('asset')->load($value['target_id']);
      }
      if ($value instanceof AssetInterface) {
        $entities[] = $value;
      }
    }
    return $entities;
  }

}
