<?php

namespace Drupal\farm_api\Controller;

use Drupal\Core\Extension\ProfileExtensionList;
use Drupal\Core\Session\AccountInterface;
use Drupal\jsonapi\Controller\EntryPoint;
use Drupal\jsonapi\JsonApiResource\JsonApiDocumentTopLevel;
use Drupal\jsonapi\JsonApiResource\NullIncludedData;
use Drupal\jsonapi\JsonApiResource\ResourceObjectData;
use Drupal\jsonapi\ResourceResponse;
use Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extend the core jsonapi EntryPoint controller.
 *
 * Adds a "meta.farm" key to root /api endpoint.
 *
 * @ingroup farm
 */
class FarmEntryPoint extends EntryPoint {

  /**
   * Farm profile info.
   *
   * @var mixed[]
   */
  protected $farmProfileInfo;

  /**
   * EntryPoint constructor.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface $resource_type_repository
   *   The resource type repository.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   * @param \Drupal\Core\Extension\ProfileExtensionList $profile_extension_list
   *   The profile extension list service.
   */
  public function __construct(ResourceTypeRepositoryInterface $resource_type_repository, AccountInterface $user, ProfileExtensionList $profile_extension_list) {
    parent::__construct($resource_type_repository, $user);
    $this->farmProfileInfo = $profile_extension_list->getExtensionInfo('farm');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('jsonapi.resource_type.repository'),
      $container->get('current_user'),
      $container->get('extension.list.profile')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function index() {

    // Get the base url.
    global $base_url;

    // Get normal response cache and data.
    $response = parent::index();
    $cacheability = $response->getCacheableMetadata();
    $data = $response->getResponseData();

    // Get urls and meta.
    $urls = $data->getLinks();
    $meta = $data->getMeta();

    // Add a "farm" object to meta.
    $meta['farm'] = [
      'name' => $this->config('system.site')->get('name'),
      'url' => $base_url,
      'version' => $this->farmProfileInfo['version'],
    ];

    // Build a new response.
    $new_response = new ResourceResponse(new JsonApiDocumentTopLevel(new ResourceObjectData([]), new NullIncludedData(), $urls, $meta));

    // Add the original response's cacheability.
    $new_response->addCacheableDependency($cacheability);

    return $new_response;
  }

}
