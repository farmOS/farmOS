<?php

namespace Drupal\farm_id_tag\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'id tag' formatter.
 *
 * @FieldFormatter(
 *   id = "id_tag",
 *   label = @Translation("ID tag"),
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

      // Render the ID if it exists.
      if (!empty($item->id)) {
        $elements[$delta]['id'] = [
          '#markup' => $this->t('ID: @value', ['@value' => $item->id]),
        ];
      }

      // Render the type if it exists.
      if (!empty($item->type)) {
        $elements[$delta]['type'] = [
          '#markup' => $this->t('Type: @value', ['@value' => $item->type]),
        ];
      }

      // Render the location if it exists.
      if (!empty($item->location)) {
        $elements[$delta]['location'] = [
          '#markup' => $this->t('Location: @value', ['@value' => $item->location]),
        ];
      }
    }

    return $elements;
  }

}
