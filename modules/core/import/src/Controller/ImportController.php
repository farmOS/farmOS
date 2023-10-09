<?php

namespace Drupal\farm_import\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Import controller.
 */
class ImportController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * Constructs a new ImportController.
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
   * The index of importers.
   *
   * @return array
   *   Returns a render array.
   */
  public function index(): array {

    // Load all menu links below it.
    $parameters = new MenuTreeParameters();
    $parameters->setRoot('farm.import')->excludeRoot()->setTopLevelOnly()->onlyEnabledLinks();
    $tree = $this->menuLinkTree->load(NULL, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuLinkTree->transform($tree, $manipulators);

    // Start cacheability for indexer list.
    $tree_access_cacheability = new CacheableMetadata();

    $links = [];
    foreach ($tree as $element) {
      $tree_access_cacheability->addCacheableDependency($element->access);

      // Only render accessible links.
      if (!$element->access->isAllowed()) {
        continue;
      }

      // Include the link.
      $links[] = $element->link;
    }
    if (!empty($links)) {
      $items = [];
      foreach ($links as $link) {
        $items[] = [
          'title' => $link->getTitle(),
          'description' => $link->getDescription(),
          'url' => $link->getUrlObject(),
        ];
      }
      $output = [
        '#theme' => 'admin_block_content',
        '#content' => $items,
      ];
    }
    else {
      $output = [
        '#markup' => $this->t('You do not have any importers.'),
      ];
    }
    $tree_access_cacheability->applyTo($output);
    return $output;
  }

}
