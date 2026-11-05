<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Plugin\Menu\LocalAction;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\asset\Entity\AssetInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates an action link to add entities.
 *
 * The 'entity_type' must be set in the action link configuration.
 */
class AddEntity extends LocalActionDefault {

  use StringTranslationTrait;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteProviderInterface $route_provider,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $route_provider);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // @todo Use autowiring and remove this when the parent class does.
    // @see https://www.drupal.org/project/drupal/issues/3552110
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
  public function getTitle(?Request $request = NULL) {

    // Get the entity type.
    $entity_type = $this->entityTypeManager->getDefinition($this->pluginDefinition['entity_type']);

    // Get the entity type label.
    $entity_type_label = $entity_type->getLabel();

    // Get the bundle machine name.
    $route_match = RouteMatch::createFromRequest($request);
    $bundle = $this->getBundle($route_match);

    // Get the bundle label.
    $bundle_label = $this->entityTypeManager->getStorage($entity_type->getBundleEntityType())->load($bundle)->label();

    // Build the link title.
    return $this->t('Add @entity_type: @bundle', ['@entity_type' => $entity_type_label, '@bundle' => $bundle_label])->render();
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(RouteMatchInterface $route_match) {
    $options = parent::getOptions($route_match);

    // Bail if there are no fields to prepopulate.
    if (empty($this->pluginDefinition['prepopulate'])) {
      return $options;
    }

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
        $param = 'asset';
        $options['query'][$param] = $asset_id;
      }
    }

    // Prepopulate log owners from the user whose logs are being viewed.
    if (!empty($this->pluginDefinition['prepopulate']['owner']['route_parameter'])) {
      $account = $route_match->getParameter($this->pluginDefinition['prepopulate']['owner']['route_parameter']);
      $owner_id = $account instanceof UserInterface ? $account->id() : $account;
      if (!empty($owner_id)) {
        $options['query']['owner'] = $owner_id;
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {

    // Get the entity type.
    $entity_type = $this->pluginDefinition['entity_type'];
    $entity_type_param = $entity_type . '_type';

    // Get the bundle machine name.
    $bundle = $this->getBundle($route_match);

    // Set the entity_type parameter for the entity.type.add_form route.
    return [
      $entity_type_param => $bundle,
    ];
  }

  /**
   * Get the bundle machine name.
   *
   * This will first look for an explicit bundle set in the plugin definition.
   * If that fails, then it will look for a bundle parameter in the route.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Route match object.
   *
   * @return string
   *   Bundle machine name.
   */
  protected function getBundle(RouteMatchInterface $route_match) {
    $bundle = NULL;
    if (!empty($this->pluginDefinition['bundle'])) {
      $bundle = $this->pluginDefinition['bundle'];
    }
    elseif (!empty($this->pluginDefinition['bundle_parameter'])) {
      $bundle = $route_match->getParameter($this->pluginDefinition['bundle_parameter']);
    }
    return $bundle;
  }

}
