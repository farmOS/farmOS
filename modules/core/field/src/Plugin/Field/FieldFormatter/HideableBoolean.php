<?php

namespace Drupal\farm_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\BooleanFormatter;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'hideable_boolean' formatter.
 *
 * Extends the core BooleanFormatter to provide settings to conditionally hide
 * a field.
 *
 * @FieldFormatter(
 *   id = "hideable_boolean",
 *   label = @Translation("Hideable Boolean"),
 *   field_types = {
 *     "boolean",
 *   }
 * )
 */
class HideableBoolean extends BooleanFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = parent::defaultSettings();

    $settings['hide_if_true'] = FALSE;
    $settings['hide_if_false'] = FALSE;

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['hide_if_true'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide if TRUE'),
      '#default_value' => $this->getSetting('hide_if_true'),
    ];
    $form['hide_if_false'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide if FALSE'),
      '#default_value' => $this->getSetting('hide_if_false'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $formats = $this->getOutputFormats();

    foreach ($items as $delta => $item) {
      $format = $this->getSetting('format');

      $hide_if_true = (bool) $this->getSetting('hide_if_true');
      $hide_if_false = (bool) $this->getSetting('hide_if_false');

      // If the item should be hidden, skip it.
      if ($item->value && $hide_if_true || !$item->value && $hide_if_false) {
        continue;
      }

      if ($format == 'custom') {
        $elements[$delta] = ['#markup' => $item->value ? $this->getSetting('format_custom_true') : $this->getSetting('format_custom_false')];
      }
      else {
        $elements[$delta] = ['#markup' => $item->value ? $formats[$format][0] : $formats[$format][1]];
      }
    }

    return $elements;
  }

}
