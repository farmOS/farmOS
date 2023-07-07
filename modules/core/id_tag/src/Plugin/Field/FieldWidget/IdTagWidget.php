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
      '#default_value' => $items[$delta]->id ?? NULL,
    ];

    // Load the saved tag type, if any.
    $tag_type = $items[$delta]->type ?? NULL;

    // Get the current asset bundle.
    $bundle = $items->getParent()->getEntity()->bundle();

    // Load allowed tag types.
    $tag_types = farm_id_tag_type_options($bundle);

    $element['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Tag type'),
      '#options' => [NULL => ''] + $tag_types,
      '#default_value' => $tag_type,
    ];

    // If the tag type is not in the list of allowed values, change to a
    // text field so that it is still editable.
    if (!empty($tag_type) && !array_key_exists($tag_type, $tag_types)) {
      $element['type']['#type'] = 'textfield';
      unset($element['type']['#options']);
    }

    $element['location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tag location'),
      '#default_value' => $items[$delta]->location ?? NULL,
    ];

    return $element;
  }

}
