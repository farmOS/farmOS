<?php

namespace Drupal\farm_ui_views\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates an action link to add entities.
 *
 * The 'entity_type' must be set in the action link configuration.
 */
class AddEntity extends LocalActionDefault {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getTitle(Request $request = NULL) {

    // Get the entity type.
    /** @var \Drupal\Core\Entity\EntityTypeInterface $entity_type */
    $entity_type = \Drupal::entityTypeManager()->getDefinition($this->pluginDefinition['entity_type']);

    // Get the bundle machine name.
    $route_match = RouteMatch::createFromRequest($request);
    $bundle = $route_match->getparameter('arg_0');

    // Get the bundle label.
    $bundle_label = \Drupal::entityTypeManager()->getStorage($entity_type->getBundleEntityType())->load($bundle)->label();

    // Build the link title.
    return $this->t('Add @bundle', ['@bundle' => $bundle_label]);
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {

    // Get the entity type.
    $entity_type = $this->pluginDefinition['entity_type'];
    $entity_type_param = $entity_type . '_type';

    // Set the entity_type parameter for the entity.type.add_form route.
    return [
      $entity_type_param => $route_match->getParameter('arg_0'),
    ];
  }

}
