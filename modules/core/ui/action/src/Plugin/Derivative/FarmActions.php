<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_ui_action\Plugin\Menu\LocalAction\AddEntity;
use Drupal\farm_ui_action\Plugin\Menu\LocalAction\EntityAction;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines farmOS action links.
 */
class FarmActions extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected ModuleHandlerInterface $moduleHandler,
    protected EntityTypeBundleInfoInterface $entityTypeBundleInfo,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    // @todo Remove when DeriverBase provides a create() method with autowiring.
    // @see https://www.drupal.org/project/drupal/issues/3565338
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
      'organization',
      'plan',
    ];

    // Load all available entity actions.
    $entity_actions = $this->entityTypeManager->getStorage('action')->loadMultiple();

    // Iterate through the farmOS entity types.
    foreach ($farm_types as $type) {

      // If the entity type does not exist, skip it.
      if (!in_array($type, $entity_types)) {
        continue;
      }

      // Generate a link to [entity-type]/add/[bundle].
      $name = 'farm.add.' . $type . '.bundle';
      $this->derivatives[$name] = $base_plugin_definition;
      $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_form';
      $this->derivatives[$name]['class'] = AddEntity::class;
      $this->derivatives[$name]['entity_type'] = $type;

      // Add the entity_bundles cache tag so action links are recreated after
      // new bundles are installed.
      $this->derivatives[$name]['cache_tags'] = ['entity_bundles'];

      // Add it to entity bundle Views, if the farm_ui_views module is enabled.
      if ($this->moduleHandler->moduleExists('farm_ui_views')) {
        $this->derivatives[$name]['appears_on'][] = 'view.farm_' . $type . '.page_type';
        $this->derivatives[$name]['bundle_parameter'] = 'arg_0';
      }

      // Generate links to /log/add/[bundle]?asset=[id] on asset pages.
      if ($type == 'log') {
        $bundles = $this->entityTypeBundleInfo->getBundleInfo('log');
        foreach ($bundles as $bundle => $bundle_info) {
          $name = 'farm.asset.add.' . $type . '.' . $bundle;
          $this->derivatives[$name] = $base_plugin_definition;
          $this->derivatives[$name]['route_name'] = 'entity.' . $type . '.add_form';
          $this->derivatives[$name]['class'] = AddEntity::class;
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

      // Generate action links for each entity action.
      /** @var \Drupal\system\Entity\Action[] $applicable_entity_actions */
      $applicable_entity_actions = array_filter($entity_actions, function ($action) use ($type) {
        return $action->getPlugin() instanceof EntityActionBase && $action->getType() == $type;
      });
      foreach ($applicable_entity_actions as $action) {
        $name = 'farm.action.' . $type . '.' . $action->id();
        $this->derivatives[$name] = $base_plugin_definition;
        $this->derivatives[$name]['title'] = $action->label();
        $this->derivatives[$name]['route_name'] = 'farm.action.' . $type;
        $this->derivatives[$name]['class'] = EntityAction::class;
        $this->derivatives[$name]['route_parameters'] = [
          'action' => $action->id(),
        ];
        $this->derivatives[$name]['appears_on'][] = 'entity.' . $type . '.canonical';
        $this->derivatives[$name]['weight'] = 10;
      }
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
