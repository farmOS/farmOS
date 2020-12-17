<?php

namespace Drupal\farm_entity;

use Drupal\entity\EntityViewsData;

/**
 * Extends the contrib EntityViewsData class.
 *
 * Ensures bundle fields included in the entity's views data. Also specifies
 * views plugins to use on certain fields.
 */
class FarmEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Check if it is the log entity.
    if ($this->entityType->id() == 'log') {

      // Specify plugins to sort logs by timestamp and log ID.
      $data['log_field_data']['timestamp']['sort']['id'] = 'log_standard';
      $data['log_field_data']['timestamp']['field']['id'] = 'log_field';
    }

    return $data;
  }

}
