<?php

namespace Drupal\farm_ui_location\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for asset drag and drop routes.
 */
class AssetReorderController extends ControllerBase implements AssetReorderControllerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds the response.
   */
  public function access(AccountInterface $account, AssetInterface $asset = NULL) {
    $permission_access = AccessResult::allowedIfHasPermission($account, 'administer assets');
    $permission_access = AccessResult::allowedIf($this->getAssetChildren($asset));
    if (!$permission_access->isAllowed()) {
      $permission_access = AccessResult::allowedIf($this->getAssetChildren($asset));
    }

    return $permission_access;
  }

  /**
   * Builds the response.
   */
  public function build(AssetInterface $asset = NULL) {
    $build['toggle_drag_and_drop'] = [
      '#type' => 'link',
      '#title' => $this->t('Toggle drag and drop'),
      '#url' => Url::fromUserInput('#'),
      '#attributes' => [
        'class' => [
          'locations-tree-toggle',
        ],
      ],
    ];

    $build['content'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'locations-tree',
        ],
      ],
    ];

    $build['save'] = [
      '#type' => 'link',
      '#title' => $this->t('Save'),
      '#url' => Url::fromRoute('<none>'),
      '#attributes' => [
        'class' => [
          'locations-tree-save',
          'button',
          'button--primary',
        ],
      ],
    ];
    $build['reset'] = [
      '#type' => 'link',
      '#title' => $this->t('Reset'),
      '#url' => Url::fromRoute('<none>'),
      '#attributes' => [
        'class' => [
          'locations-tree-reset',
          'button',
          'button--danger',
        ],
      ],
    ];

    $build['#attached']['library'][] = 'farm_ui_location/locations-drag-and-drop';
    $tree = [
      [
        'uuid' => $asset->uuid(),
        'text' => $asset->label(),
        'children' => $this->buildTree($asset),
        'type' => $asset->bundle(),
        'url' => $asset->toUrl('canonical', ['absolute' => TRUE])->toString(),
      ]
    ];
    $build['#attached']['drupalSettings']['asset_tree'] = $tree;
    $build['#attached']['drupalSettings']['asset_parent'] = !empty($asset) ? $asset->uuid() : '';
    $build['#attached']['drupalSettings']['asset_parent_type'] = !empty($asset) ? $asset->bundle() : '';
    return $build;
  }

  /**
   * Build the asset tree.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   Optionally specify the parent asset, to only build a sub-tree. If
   *   omitted, all assets will be included.
   *
   * @return array
   *   Returns the asset tree for use in Drupal JS settings.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function buildTree(AssetInterface $asset = NULL) {
    $children = $this->getAssetChildren($asset);
    $tree = [];
    if ($children) {
      foreach ($children as $child) {
        $element = [
          'uuid' => $child->uuid(),
          'text' => $child->label(),
          'children' => $this->buildTree($child),
          'type' => $child->bundle(),
          'url' => $child->toUrl('canonical', ['absolute' => TRUE])->toString(),
        ];
        $element['original_parent'] = $asset ? $asset->uuid() : '';
        $element['original_type'] = $asset ? $asset->bundle() : '';
        $tree[] = $element;
      }
    }

    return $tree;
  }

  /**
   * Gets the children assets from a given asset.
   *
   * TODO: Should this be part of the asset entity so $asset->getChildren() can
   * be called?
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The partent asset.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]|false
   *   An array of children, FALSE if none found.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getAssetChildren(AssetInterface $asset = NULL) {
    if (empty($asset)) {
      return FALSE;
    }
    $storage = $this->entityTypeManager->getStorage('asset');
    $query = $storage->getQuery();
    $query->condition('is_location', TRUE);
    if ($asset) {
      $query->condition('parent', $asset->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }
    $query->sort('name');

    $asset_ids = $query->execute();
    if (empty($asset_ids)) {
      return FALSE;
    }
    /** @var \Drupal\asset\Entity\AssetInterface[] $children */
    $children = $storage->loadMultiple($asset_ids);
    return $children;
  }

}
