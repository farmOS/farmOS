<?php

namespace Drupal\asset\ContextProvider;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\Context\Context;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\Plugin\Context\EntityContextDefinition;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the current asset as a context on asset routes.
 */
class AssetRouteContext implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * The route match object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new AssetRouteContext.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $result = [];
    $context_definition = EntityContextDefinition::create('asset')->setRequired(FALSE);
    $value = NULL;
    if (($route_object = $this->routeMatch->getRouteObject())) {
      $route_contexts = $route_object->getOption('parameters');
      // Check for an asset revision parameter first.
      if (isset($route_contexts['asset_revision']) && $revision = $this->routeMatch->getParameter('asset_revision')) {
        $value = $revision;
      }
      elseif (isset($route_contexts['asset']) && $asset = $this->routeMatch->getParameter('asset')) {
        $value = $asset;
      }
      elseif ($this->routeMatch->getRouteName() == 'asset.add') {
        $asset_type = $this->routeMatch->getParameter('asset_type');
        $value = Asset::create(['type' => $asset_type->id()]);
      }
    }

    $cacheability = new CacheableMetadata();
    $cacheability->setCacheContexts(['route']);

    $context = new Context($context_definition, $value);
    $context->addCacheableDependency($cacheability);
    $result['asset'] = $context;

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityTypeId('asset', $this->t('Asset from URL'));
    return ['asset' => $context];
  }

}
