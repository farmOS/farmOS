<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Hook;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Menu\LocalActionManagerInterface;

/**
 * Entity hook implementations for farm_ui_action.
 */
class EntityHooks {

  public function __construct(
    protected LocalActionManagerInterface $localActionManager,
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_create().
   */
  #[Hook('action_create')]
  public function actionCreate(EntityInterface $action) {

    // Clear the menu local action plugin cache when an action is created.
    // PHPStan throws the following error on the next line:
    // Call to an undefined method
    // Drupal\Core\Menu\LocalActionManagerInterface::clearCachedDefinitions().
    // This is because the clearCachedDefinitions() method is defined in
    // Drupal\Core\Plugin\DefaultPluginManager, which implements
    // CachedDiscoveryInterface, but LocalActionManagerInterface extends
    // PluginManagerInterface, which only implements DiscoveryInterface (without
    // the caching methods). We ignore this error because we don't expect the
    // plugin.manager.menu.local_action service class to be overridden with a
    // class that doesn't extend from DefaultPluginManager, so the method we
    // need should always be available.
    // @phpstan-ignore method.notFound
    $this->localActionManager->clearCachedDefinitions();
  }

}
