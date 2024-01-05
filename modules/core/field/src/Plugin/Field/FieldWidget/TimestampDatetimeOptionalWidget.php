<?php

namespace Drupal\farm_field\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\Plugin\Field\FieldWidget\TimestampDatetimeWidget;
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
