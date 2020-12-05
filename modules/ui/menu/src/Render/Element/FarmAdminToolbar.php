<?php

namespace Drupal\farm_ui_menu\Render\Element;

use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Provides a preRenderTray() method for the toolbar that uses farm.base.
 *
 * @package Drupal\farm_ui_menu\Render\Element
 */
class FarmAdminToolbar implements TrustedCallbackInterface {

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['preRenderTray'];
  }

  /**
   * Renders the farmOS toolbar's administration tray.
   *
   * This is a clone of AdminToolbar::preRenderTray() method, which sets the
   * menu root to farm.base instead of system.admin.
   *
   * @param array $build
   *   A renderable array.
   *
   * @return array
   *   The updated renderable array.
   *
   * @see \Drupal\admin_toolbar\Render\Element\AdminToolbar::preRenderTray()
   */
  public static function preRenderTray(array $build) {
    $menu_tree = \Drupal::service('toolbar.menu_tree');
    $parameters = new MenuTreeParameters();
    $parameters->setRoot('farm.base')->excludeRoot()->setMaxDepth(4)->onlyEnabledLinks();
    $tree = $menu_tree->load(NULL, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
      ['callable' => 'toolbar_tools_menu_navigation_links'],
    ];
    $tree = $menu_tree->transform($tree, $manipulators);
    $build['administration_menu'] = $menu_tree->build($tree);
    return $build;
  }

}
