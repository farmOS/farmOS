<?php

namespace Drupal\farm_ui_menu\Plugin\Menu\LocalAction;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates an action link to add logs.
 *
 * The 'prepopulate' key from the action link configuration is used to specify
 * which field and value to prepopulate.
 */
class FarmAddLogPrepopulate extends LocalActionDefault {

  use StringTranslationTrait;

  /**
   * The redirect destination.
   *
   * @var \Drupal\Core\Routing\RedirectDestinationInterface
   */
  private $redirectDestination;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Constructs a FarmAddLogPrepopulate object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   The route provider to load routes by name.
   * @param \Drupal\Core\Routing\RedirectDestinationInterface $redirect_destination
   *   The redirect destination.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteProviderInterface $route_provider, RedirectDestinationInterface $redirect_destination, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $route_provider);

    $this->redirectDestination = $redirect_destination;
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
      $container->get('redirect.destination'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(RouteMatchInterface $route_match) {
    $options = parent::getOptions($route_match);

    // Append the current path as destination to the query string.
    $options['query']['destination'] = $this->redirectDestination->get();

    // Bail if there are no fields to prepopulate.
    if (empty($this->pluginDefinition['prepopulate'])) {
      return $options;
    }

    // @todo Support prepopulating other fields on logs.
    // This could likely be generalized by using the field type definition
    // to build the prepopulate query parameters. The format for entity
    // reference fields is different than simple text fields, etc.
    // Check if there is an asset field to prepopulate.
    if (!empty($this->pluginDefinition['prepopulate']['asset'])) {

      $asset_id = NULL;

      // If an asset id is specified, use it.
      if (!empty($this->pluginDefinition['prepopulate']['asset']['id'])) {
        $asset_id = $this->pluginDefinition['prepopulate']['asset']['id'];
      }

      // If a route parameter is specified, use it instead.
      if (!empty($this->pluginDefinition['prepopulate']['asset']['route_parameter'])) {

        // Get the asset.
        $asset_param = $this->pluginDefinition['prepopulate']['asset']['route_parameter'];
        $asset = $route_match->getParameter($asset_param);

        // If the parameter returned an entity, get its ID.
        if ($asset instanceof AssetInterface) {
          $asset_id = $asset->id();
        }
        // Else, assume the parameter is the asset ID.
        else {
          $asset_id = $asset;
        }
      }

      // Continue if the asset_id was found.
      if (!empty($asset_id)) {

        // Build a query param to prepopulate the asset field in the log form.
        $param = 'edit[asset][widget][0][target_id]';
        $options['query'][$param] = $asset_id;
      }
    }

    return $options;
  }

}
