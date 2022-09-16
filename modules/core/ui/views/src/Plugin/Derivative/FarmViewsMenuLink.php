<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\views\Plugin\Derivative\ViewsMenuLink;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides menu links for farmOS Views.
 *
 * @see \Drupal\views\Plugin\Menu\ViewsMenuLink
 */
class FarmViewsMenuLink extends ViewsMenuLink {

  /**
   * Set this in child classes.
   *
   * @var string
   *
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmAssetViewsMenuLink
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmLogViewsMenuLink
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmQuantityViewsMenuLink
   */
  protected string $entityType;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a FarmActions object.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $view_storage
   *   The view storage.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityStorageInterface $view_storage, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($view_storage);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager')->getStorage('view'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Get the entity type definition. Bail if invalid.
    $entity_type_definition = $this->entityTypeManager->getDefinition($this->entityType, FALSE);
    if (empty($entity_type_definition)) {
      return $links;
    }

    // Get the bundle entity type.
    $bundle_entity_type = $entity_type_definition->getBundleEntityType();

    // Load all available bundles for the entity type.
    $bundles = $this->entityTypeManager->getStorage($bundle_entity_type)->loadMultiple();

    // Add links for each bundle.
    foreach ($bundles as $type => $bundle) {
      $links['farm.' . $this->entityType . '.' . $type] = [
        'title' => $bundle->label(),
        'parent' => 'views_view:views.farm_' . $this->entityType . '.page',
        'route_name' => 'view.farm_' . $this->entityType . '.page_type',
        'route_parameters' => ['arg_0' => $type],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
