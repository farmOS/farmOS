<?php

namespace Drupal\farm_id_tag\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'id tag' formatter.
 *
 * @FieldFormatter(
 *   id = "farm_id_tag",
 *   label = @Translation("Farm Id Tag"),
 *   field_types = {
 *     "farm_id_tag"
 *   }
 * )
 */
class FarmIdTagFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta]['id'] = ['#markup' => $item->id];
      $elements[$delta]['type'] = ['#markup' => $item->type];
      $elements[$delta]['location'] = ['#markup' => $item->location];
    }

    return $elements;
  }

}
