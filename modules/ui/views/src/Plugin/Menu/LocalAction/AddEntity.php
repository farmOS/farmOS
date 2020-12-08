<?php

namespace Drupal\farm_ui_views\Plugin\Menu\LocalAction;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates an action link to add entities.
 *
 * The 'entity_type' must be set in the action link configuration.
 */
class AddEntity extends LocalActionDefault {

  use StringTranslationTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Constructs an AddEntity object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   The route provider to load routes by name.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteProviderInterface $route_provider, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $route_provider);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('router.route_provider'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle(Request $request = NULL) {

    // Get the entity type.
    $entity_type = $this->entityTypeManager->getDefinition($this->pluginDefinition['entity_type']);

    // Get the bundle machine name.
    $route_match = RouteMatch::createFromRequest($request);
    $bundle = $route_match->getparameter('arg_0');

    // Get the bundle label.
    $bundle_label = $this->entityTypeManager->getStorage($entity_type->getBundleEntityType())->load($bundle)->label();

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
