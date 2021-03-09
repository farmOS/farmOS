<?php

namespace Drupal\quantity;

use Drupal\entity\EntityViewsData;

/**
 * Provides the views data for the quantity entity type.
 */
class QuantityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Create a value field, filter, and sort using fraction decimal handlers.
    $fraction_fields = [
      'numerator' => 'value__numerator',
      'denominator' => 'value__denominator',
    ];
    $data['quantity']['value'] = [
      'title' => $this->t('Value'),
      'help' => $this->t('Value of the quantity, in decimal format.'),
      'real field' => 'value__numerator',
      'field' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
        'click sortable' => TRUE,
      ],
      'sort' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
      ],
      'filter' => [
        'id' => 'fraction',
        'additional fields' => $fraction_fields,
      ],
    ];

    return $data;
  }

}
