<?php

namespace Drupal\farm_setup\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Setup controller.
 */
class SetupController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * Constructs a new SetupController.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree) {
    $this->menuLinkTree = $menu_link_tree;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('menu.link_tree')
    );
  }

  /**
   * The index of reports.
   *
   * @return array
   *   Returns a render array.
   */
  public function index(): array {

    // Load all menu links below it.
    $parameters = new MenuTreeParameters();
    $parameters->setRoot('farm.setup')->excludeRoot()->setTopLevelOnly()->onlyEnabledLinks();
    $tree = $this->menuLinkTree->load(NULL, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuLinkTree->transform($tree, $manipulators);
    $tree_access_cacheability = new CacheableMetadata();
    $items = [];
    foreach ($tree as $element) {
      $tree_access_cacheability = $tree_access_cacheability->merge(CacheableMetadata::createFromObject($element->access));

      // Only render accessible links.
      if (!$element->access->isAllowed()) {
        continue;
      }

      // Include the link.
      $items[] = [
        'title' => $element->link->getTitle(),
        'description' => $element->link->getDescription(),
        'url' => $element->link->getUrlObject(),
      ];
    }

    // Create render array with cacheability.
    $render = [];
    $tree_access_cacheability->applyTo($render);

    // Add message if there are no setup items.
    if (empty($items)) {
      $render['#markup'] = $this->t('You do not have any setup items.');
    }
    else {
      $render['#theme'] = 'admin_block_content';
      $render['#content'] = $items;
    }

    return $render;
  }

}
