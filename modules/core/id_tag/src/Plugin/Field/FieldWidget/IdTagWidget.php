<?php

namespace Drupal\farm_id_tag\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'id tag' widget.
 *
 * @FieldWidget(
 *   id = "id_tag",
 *   label = @Translation("ID tag"),
 *   field_types = {
 *     "id_tag"
 *   }
 * )
 */
class IdTagWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element['#type'] = 'fieldset';

    $element['id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tag ID'),
      '#default_value' => isset($items[$delta]->id) ? $items[$delta]->id : NULL,
    ];

    $element['type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tag type'),
      '#default_value' => isset($items[$delta]->type) ? $items[$delta]->type : NULL,
    ];

    $element['location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tag location'),
      '#default_value' => isset($items[$delta]->location) ? $items[$delta]->location : NULL,
    ];

    return $element;
  }

}
