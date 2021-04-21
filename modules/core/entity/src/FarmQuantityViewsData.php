<?php

namespace Drupal\farm_entity;

use Drupal\quantity\QuantityViewsData;

/**
 * Provides the views data for the quantity entity type.
 */
class FarmQuantityViewsData extends QuantityViewsData {

  use EntityViewsDataTaxonomyFilterTrait;

}
