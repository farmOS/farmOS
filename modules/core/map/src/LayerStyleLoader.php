<?php

namespace Drupal\farm_map;

use Drupal\farm_map\Entity\LayerStyle;
use Drupal\farm_map\Entity\LayerStyleInterface;

/**
 * Layer style loader.
 */
class LayerStyleLoader implements LayerStyleLoaderInterface {

  /**
   * {@inheritdoc}
   */
  public function load(array $conditions = []): ?LayerStyleInterface {

    // Load all LayerStyle config entities.
    /** @var \Drupal\farm_map\Entity\LayerStyleInterface[] $layer_styles */
    $layer_styles = LayerStyle::loadMultiple();

    // If there are conditions, filter the styles.
    if (!empty($conditions)) {
      foreach ($conditions as $key => $value) {
        $layer_styles = array_filter($layer_styles, function ($layer_style) use ($key, $value) {
          $style_conditions = $layer_style->getConditions();
          if (isset($style_conditions[$key])) {
            if (is_array($style_conditions[$key]) && in_array($value, $style_conditions[$key])) {
              return TRUE;
            }
            if ($style_conditions[$key] == $value) {
              return TRUE;
            }
          }
          return FALSE;
        });
      }
    }

    // If the filtered styles are not empty, return the first one.
    if (!empty($layer_styles)) {
      return reset($layer_styles);
    }

    return NULL;
  }

}
