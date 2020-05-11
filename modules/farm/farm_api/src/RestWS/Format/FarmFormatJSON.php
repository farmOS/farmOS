<?php

namespace Drupal\farm_api\RestWS\Format;

use RestWSFormatJSON;

/**
 * Overrides the default restws response with a custom farmOS one.
 *
 * This leverages xautoload for PSR-4 loading.
 *
 * @package Drupal\farm_api\RestWs\Format
 *
 * @see farm_api_restws_format_info_alter()
 */
class FarmFormatJSON extends RestWSFormatJSON {

  public function getResourceReference($resource, $id) {
    $return = parent::getResourceReference($resource, $id);

    // If the resource is a taxonomy term, show the term name alongside its ID.
    if ($resource == 'taxonomy_term') {
      $term = taxonomy_term_load($id);
      $return['name'] = $term->name;
    }

    return $return;
  }

}
