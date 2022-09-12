<?php

namespace Drupal\farm_api\Controller;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ProfileExtensionList;
use Drupal\Core\Session\AccountInterface;
use Drupal\jsonapi\CacheableResourceResponse;
use Drupal\jsonapi\Controller\EntryPoint;
use Drupal\jsonapi\JsonApiResource\JsonApiDocumentTopLevel;
use Drupal\jsonapi\JsonApiResource\NullIncludedData;
use Drupal\jsonapi\JsonApiResource\ResourceObjectData;
use Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extend the core jsonapi EntryPoint controller.
 *
 * Adds a "meta.farm" key to root /api endpoint.
 *
 * @ingroup farm
 *
 * @phpstan-ignore-next-line
 */
class FarmEntryPoint extends EntryPoint {

  /**
   * Farm profile info.
   *
   * @var mixed[]
   */
  protected $farmProfileInfo;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * EntryPoint constructor.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface $resource_type_repository
   *   The resource type repository.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   * @param \Drupal\Core\Extension\ProfileExtensionList $profile_extension_list
   *   The profile extension list service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ResourceTypeRepositoryInterface $resource_type_repository, AccountInterface $user, ProfileExtensionList $profile_extension_list, ModuleHandlerInterface $module_handler) {
    parent::__construct($resource_type_repository, $user);
    $this->farmProfileInfo = $profile_extension_list->getExtensionInfo('farm');
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('jsonapi.resource_type.repository'),
      $container->get('current_user'),
      $container->get('extension.list.profile'),
      $container->get('module_handler'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function index() {

    // Get the base url.
    global $base_url;

    // Get normal response cache and data.
    /** @var \Drupal\jsonapi\CacheableResourceResponse $response */
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

    // Allow modules to add additional meta information.
    $this->moduleHandler->alter('farm_api_meta', $meta['farm']);

    // Build a new response.
    $new_response = new CacheableResourceResponse(new JsonApiDocumentTopLevel(new ResourceObjectData([]), new NullIncludedData(), $urls, $meta));

    // Add the original response's cacheability.
    $new_response->addCacheableDependency($cacheability);

    return $new_response;
  }

}
