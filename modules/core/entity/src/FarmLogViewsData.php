<?php

namespace Drupal\farm_entity;

use Drupal\log\LogViewsData;

/**
 * Provides the views data for the log entity type.
 */
class FarmLogViewsData extends LogViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    return $data;
  }

}
