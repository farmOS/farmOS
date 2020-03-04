<?php

namespace Drupal\id_tag\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'id tag' formatter.
 *
 * @FieldFormatter(
 *   id = "id_tag",
 *   label = @Translation("Id Tag"),
 *   field_types = {
 *     "id_tag"
 *   }
 * )
 */
class IdTagFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta]['id'] = ['#markup' => $item->id];
      $elements[$delta]['type'] = ['#markup' => $item->type];
      $elements[$delta]['body_location'] = ['#markup' => $item->body_location];
    }

    return $elements;
  }

}
