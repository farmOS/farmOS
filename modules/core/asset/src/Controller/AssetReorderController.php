<?php

namespace Drupal\asset\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for asset drag and drop routes.
 */
class AssetReorderController extends ControllerBase {

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
  public function build(AssetInterface $asset = NULL) {
    $build['content'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'asset-tree',
        ],
      ],
    ];

    $build['save'] = [
      '#type' => 'link',
      '#title' => $this->t('Save'),
      '#url' => Url::fromRoute('<none>'),
      '#attributes' => [
        'class' => [
          'asset-tree-save',
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
          'asset-tree-reset',
          'button',
          'button--danger',
        ],
      ],
    ];

    $build['#attached']['library'][] = 'asset/reorder';
    $build['#attached']['drupalSettings']['asset_tree'] = $this->buildTree($asset);
    $build['#attached']['drupalSettings']['asset_parent'] = !empty($asset) ? $asset->uuid() : '';
    $build['#attached']['drupalSettings']['asset_parent_type'] = !empty($asset) ? $asset->bundle() : '';
    return $build;
  }

  /**
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function buildTree(AssetInterface $asset = NULL) {
    $storage = $this->entityTypeManager->getStorage('asset');
    $query = $storage->getQuery();
    if ($asset) {
      $query->condition('parent', $asset->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }
    $query->sort('name');

    $asset_ids = $query->execute();
    /** @var \Drupal\asset\Entity\AssetInterface $children */
    $children = $storage->loadMultiple($asset_ids);
    $tree = [];
    foreach ($children as $child) {
      $element = [
        'uuid' => $child->uuid(),
        'text' => $child->label(),
        'children' => $this->buildTree($child),
        'type' => $child->bundle(),
      ];
      $element['original_parent'] = $asset ? $asset->uuid() : '';
      $element['original_type'] = $asset ? $asset->bundle() : '';
      $tree[] = $element;
    }

    return $tree;
  }

}
