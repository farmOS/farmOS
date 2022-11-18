<?php

namespace Drupal\farm_ui_action\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines farmOS action links.
 */
class FarmActions extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs a FarmActions object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler, EntityTypeBundleInfoInterface $entity_type_bundle_info) {
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('entity_type.bundle.info'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Load available entity types.
    $entity_types = array_keys($this->entityTypeManager->getDefinitions());

    // Define the farmOS entity types we care about.
    $farm_types = [
      'asset',
      'log',
      'plan',
    ];

    // Iterate through the farmOS entity types.
    foreach ($farm_types as $type) {

      // If the entity type does not exist, skip it.
      if (!in_array($type, $entity_types)) {
        continue;
      }

      // Generate a link to [entity-type]/add.
      $name = 'farm.add.' . $type;
      $entity_type_label = $this->entityTypeManager->getStorage($type)->getEntityType()->getLabel();
      $this->derivatives[$name] = $base_plugin_definition;
      $this->derivatives[$name]['title'] = $this->t('Add :entity_type', [':entity_type' => $entity_type_label]);
      $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_page';

      // Add it to entity Views, if the farm_ui_views module is enabled.
      if ($this->moduleHandler->moduleExists('farm_ui_views')) {
        $this->derivatives[$name]['appears_on'][] = 'view.farm_' . $type . '.page';

        // If this is a log, also add it to view.farm_log.page_user.
        if ($type == 'log') {
          $this->derivatives[$name]['appears_on'][] = 'view.farm_log.page_user';
        }
      }

      // Add it to farm.dashboard, if the farm_ui_dashboard module is enabled.
      if ($this->moduleHandler->moduleExists('farm_ui_dashboard')) {
        $this->derivatives[$name]['appears_on'][] = 'farm.dashboard';
      }

      // Generate a link to [entity-type]/add/[bundle].
      $name = 'farm.add.' . $type . '.bundle';
      $this->derivatives[$name] = $base_plugin_definition;
      $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_form';
      $this->derivatives[$name]['class'] = 'Drupal\farm_ui_action\Plugin\Menu\LocalAction\AddEntity';
      $this->derivatives[$name]['entity_type'] = $type;

      // Add the entity_bundles cache tag so action links are recreated after
      // new bundles are installed.
      $this->derivatives[$name]['cache_tags'] = ['entity_bundles'];

      // Add it to entity bundle Views, if the farm_ui_views module is enabled.
      if ($this->moduleHandler->moduleExists('farm_ui_views')) {
        $this->derivatives[$name]['appears_on'][] = 'view.farm_' . $type . '.page_type';
        $this->derivatives[$name]['bundle_parameter'] = 'arg_0';
      }

      // Generate links to [entity-type]/add/[bundle]?asset=[id] on asset pages.
      if ($type == 'log') {
        $bundles = $this->entityTypeBundleInfo->getBundleInfo('log');
        foreach ($bundles as $bundle => $bundle_info) {
          $name = 'farm.asset.add.' . $type . '.' . $bundle;
          $this->derivatives[$name] = $base_plugin_definition;
          $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_form';
          $this->derivatives[$name]['class'] = 'Drupal\farm_ui_action\Plugin\Menu\LocalAction\AddEntity';
          $this->derivatives[$name]['entity_type'] = $type;
          $this->derivatives[$name]['bundle'] = $bundle;
          $this->derivatives[$name]['appears_on'][] = 'entity.asset.canonical';
          $this->derivatives[$name]['prepopulate'] = [
            'asset' => [
              'route_parameter' => 'asset',
            ],
          ];
          $this->derivatives[$name]['cache_tags'] = ['entity_bundles'];

          // Add it to the /asset/%asset/logs/%log_type View, if the
          // farm_ui_views module is enabled.
          if ($this->moduleHandler->moduleExists('farm_ui_views')) {
            $this->derivatives[$name]['appears_on'][] = 'view.farm_log.page_asset';
          }
        }
      }
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
