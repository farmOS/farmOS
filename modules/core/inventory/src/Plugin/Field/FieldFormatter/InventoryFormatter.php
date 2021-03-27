<?php

namespace Drupal\farm_inventory\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'inventory' formatter.
 *
 * @FieldFormatter(
 *   id = "inventory",
 *   label = @Translation("Inventory"),
 *   field_types = {
 *     "inventory"
 *   }
 * )
 */
class InventoryFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $summary = $item->value;
      if (!empty($item->units)) {
        $summary .= ' ' . $item->units;
      }
      if (!empty($item->measure)) {
        $measures = quantity_measures();
        if (!empty($measures[$item->measure]['label'])) {
          $summary .= ' (' . $measures[$item->measure]['label'] . ')';
        }
      }
      $elements[$delta]['value'] = ['#markup' => $summary];
    }

    return $elements;
  }

}
