<?php

namespace Drupal\farm_ui_location\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\farm_location\AssetLocationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for asset drag and drop routes.
 */
class AssetReorderController extends ControllerBase implements AssetReorderControllerInterface {

  /**
   * The asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * The controller constructor.
   *
   * @param \Drupal\farm_location\AssetLocationInterface $asset_location
   *   The asset location service.
   */
  public function __construct(AssetLocationInterface $asset_location) {
    $this->assetLocation = $asset_location;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('asset.location')
    );
  }

  /**
   * Check access.
   */
  public function access(AccountInterface $account, AssetInterface $asset = NULL) {

    // If the asset is not a location, forbid access.
    if (!$this->assetLocation->isLocation($asset)) {
      return AccessResult::forbidden();
    }

    // Allow access if the asset has child locations.
    return AccessResult::allowedIf(!empty($this->getLocations($asset)));
  }

  /**
   * Generate the page title.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   Optionally specify the parent asset that this page is being built for.
   *
   * @return string
   *   Returns the translated page title.
   */
  public function getTitle(AssetInterface $asset = NULL) {
    if (!empty($asset)) {
      return $this->t('Locations in %location', ['%location' => $asset->label()]);
    }
    return $this->t('Locations');
  }

  /**
   * Builds the response.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   Optionally specify the parent asset, to only build a sub-tree. If
   *   omitted, all assets will be included.
   *
   * @return array
   *   Returns a build array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function build(AssetInterface $asset = NULL) {

    $build['content'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'locations-tree',
        ],
      ],
    ];

    $build['toggle_drag_and_drop'] = [
      '#type' => 'link',
      '#title' => $this->t('Toggle drag and drop'),
      '#url' => Url::fromUserInput('#'),
      '#attributes' => [
        'class' => [
          'locations-tree-toggle',
          'button',
          'button--secondary',
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
        'uuid' => !empty($asset) ? $asset->uuid() : '',
        'text' => !empty($asset) ? $asset->label() : $this->t('All locations'),
        'children' => !empty($asset) ? $this->buildTree($asset) : $this->buildTree(),
        'type' => !empty($asset) ? $asset->bundle() : '',
        'url' => !empty($asset) ? $asset->toUrl('canonical', ['absolute' => TRUE])->toString() : '/locations',
      ],
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
    $locations = $this->getLocations($asset);
    $tree = [];
    if ($locations) {
      foreach ($locations as $location) {
        $element = [
          'uuid' => $location->uuid(),
          'text' => $location->label(),
          'children' => $this->buildTree($location),
          'type' => $location->bundle(),
          'url' => $location->toUrl('canonical', ['absolute' => TRUE])->toString(),
        ];
        $element['original_parent'] = $asset ? $asset->uuid() : '';
        $element['original_type'] = $asset ? $asset->bundle() : '';
        $tree[] = $element;
      }
    }
    return $tree;
  }

  /**
   * Gets location assets.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   Optionally provide a parent asset to only retrieve its direct children.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   An array of location assets.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getLocations(AssetInterface $asset = NULL) {
    $storage = $this->entityTypeManager()->getStorage('asset');
    $query = $storage->getQuery();
    $query->condition('is_location', TRUE);
    $query->condition('status', 'archived', '!=');
    if ($asset) {
      $query->condition('parent', $asset->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }
    $query->sort('name');
    $asset_ids = $query->execute();
    if (empty($asset_ids)) {
      return [];
    }
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $storage->loadMultiple($asset_ids);
    return $assets;
  }

}
