<?php

namespace Drupal\farm_field\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\Element\Datetime;
use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Datetime\Plugin\Field\FieldWidget\TimestampDatetimeWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'datetime timestamp optional' widget.
 *
 * Extends the core datetime_timestamp widget to not default to current time.
 * This is useful when a timestamp field is optional and should not default
 * to the current time, such as the animal birthdate.
 *
 * @FieldWidget(
 *   id = "datetime_timestamp_optional",
 *   label = @Translation("Datetime Timestamp Optional"),
 *   field_types = {
 *     "timestamp",
 *   }
 * )
 */
class TimestampDatetimeOptionalWidget extends TimestampDatetimeWidget {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = parent::defaultSettings();
    $settings['toggle_time'] = TRUE;
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form['toggle_time'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Toggle time'),
      '#description' => $this->t('Default to time input hidden with a button to show time input.'),
      '#default_value' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $date_format = DateFormat::load('html_date')->getPattern();
    $time_format = DateFormat::load('html_time')->getPattern();
    $element['value']['#description'] = $this->t('Format: %format.', ['%format' => Datetime::formatExample($date_format . ' ' . $time_format)]);

    // Toggle the time if the setting is enabled and no value is provided
    // or if the provided value has a time of midnight.
    $toggle_time = $this->getSetting('toggle_time');
    $existing_time_midnight = FALSE;
    if (isset($items[$delta]->value)) {
      $datetime = DrupalDateTime::createFromTimestamp($items[$delta]->value);
      $existing_time_midnight = (int) $datetime->format('His') === 0;
    }
    $element['value']['#toggle_time'] = $toggle_time && $existing_time_midnight;
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$item) {
      // @todo The structure is different whether access is denied or not, to
      //   be fixed in https://www.drupal.org/node/2326533.
      if (isset($item['value']) && $item['value'] instanceof DrupalDateTime) {
        $date = $item['value'];
      }
      elseif (isset($item['value']['object']) && $item['value']['object'] instanceof DrupalDateTime) {
        $date = $item['value']['object'];
      }
      $item['value'] = !empty($date) ? $date->getTimestamp() : NULL;
    }
    return $values;
  }

}
