<?php

namespace Drupal\farm_api\Controller;

use Drupal\jsonapi\Controller\EntryPoint;
use Drupal\jsonapi\JsonApiResource\JsonApiDocumentTopLevel;
use Drupal\jsonapi\JsonApiResource\NullIncludedData;
use Drupal\jsonapi\JsonApiResource\ResourceObjectData;
use Drupal\jsonapi\ResourceResponse;

/**
 * Extend the core jsonapi EntryPoint controller.
 *
 * Adds a "meta.farm" key to root /api endpoint.
 *
 * @ingroup farm
 */
class FarmEntryPoint extends EntryPoint {

  /**
   * {@inheritdoc}
   */
  public function index() {

    // Get normal response cache and data.
    $response = parent::index();
    $cacheability = $response->getCacheableMetadata();
    $data = $response->getResponseData();

    // Get urls and meta.
    $urls = $data->getLinks();
    $meta = $data->getMeta();

    // Add a "farm" object to meta.
    $meta['farm'] = [];

    // Build a new response.
    $new_response = new ResourceResponse(new JsonApiDocumentTopLevel(new ResourceObjectData([]), new NullIncludedData(), $urls, $meta));

    // Add the original response's cacheability.
    $new_response->addCacheableDependency($cacheability);

    return $new_response;
  }

}
