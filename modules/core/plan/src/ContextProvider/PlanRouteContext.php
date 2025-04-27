<?php

namespace Drupal\plan\ContextProvider;

use Drupal\plan\Entity\Plan;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\Context\Context;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Plugin\Context\EntityContext;
use Drupal\Core\Plugin\Context\EntityContextDefinition;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Sets the current plan as a context on plan routes.
 */
class PlanRouteContext implements ContextProviderInterface {

  use StringTranslationTrait;

  /**
   * The route match object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new PlanRouteContext.
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
    $context_definition = EntityContextDefinition::create('plan')->setRequired(FALSE);
    $value = NULL;
    if (($route_object = $this->routeMatch->getRouteObject())) {
      $route_contexts = $route_object->getOption('parameters');
      // Check for a plan revision parameter first.
      if (isset($route_contexts['plan_revision']) && $revision = $this->routeMatch->getParameter('plan_revision')) {
        $value = $revision;
      }
      elseif (isset($route_contexts['plan']) && $plan = $this->routeMatch->getParameter('plan')) {
        $value = $plan;
      }
      elseif ($this->routeMatch->getRouteName() == 'plan.add') {
        $plan_type = $this->routeMatch->getParameter('plan_type');
        $value = Plan::create(['type' => $plan_type->id()]);
      }
    }

    $cacheability = new CacheableMetadata();
    $cacheability->setCacheContexts(['route']);

    $context = new Context($context_definition, $value);
    $context->addCacheableDependency($cacheability);
    $result['plan'] = $context;

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = EntityContext::fromEntityTypeId('plan', $this->t('Plan from URL'));
    return ['plan' => $context];
  }

}
