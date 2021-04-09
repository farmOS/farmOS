<?php

namespace Drupal\farm_entity;

use Drupal\entity\EntityViewsData;

/**
 * Configures the correct view filter for taxonomy_term reference fields.
 */
class FarmEntityViewsData extends EntityViewsData {

  use EntityViewsDataTaxonomyFilterTrait;

}
