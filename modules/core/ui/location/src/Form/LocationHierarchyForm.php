<?php

namespace Drupal\farm_ui_location\Form;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_location\AssetLocationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for changing the hierarchy of location assets.
 *
 * @ingroup farm
 */
class LocationHierarchyForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * Constructs a new LocationHierarchyForm.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\farm_location\AssetLocationInterface $asset_location
   *   The asset location service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AssetLocationInterface $asset_location) {
    $this->entityTypeManager = $entity_type_manager;
    $this->assetLocation = $asset_location;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('asset.location')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_ui_location_form';
  }

  /**
   * Check access.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account to check.
   * @param \Drupal\asset\Entity\AssetInterface|null $asset
   *   The asset to check (optional).
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
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
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, AssetInterface $asset = NULL) {

    // Add a DIV for the JavaScript content.
    $form['content'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'locations-tree',
        ],
      ],
    ];

    // Add buttons for toggling drag and drop, saving, and resetting.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['toggle'] = [
      '#type' => 'button',
      '#value' => $this->t('Toggle drag and drop'),
      '#attributes' => [
        'class' => [
          'button--secondary',
        ],
      ],
    ];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#attributes' => [
        'class' => [
          'button--primary',
        ],
      ],
    ];
    $form['actions']['reset'] = [
      '#type' => 'submit',
      '#value' => $this->t('Reset'),
      '#attributes' => [
        'class' => [
          'button--danger',
        ],
      ],
    ];

    // Attach the location drag and drop JavaScript.
    $form['#attached']['library'][] = 'farm_ui_location/locations-drag-and-drop';
    $tree = [
      [
        'uuid' => !empty($asset) ? $asset->uuid() : '',
        'text' => !empty($asset) ? $asset->label() : $this->t('All locations'),
        'children' => !empty($asset) ? $this->buildTree($asset) : $this->buildTree(),
        'type' => !empty($asset) ? $asset->bundle() : '',
        'url' => !empty($asset) ? $asset->toUrl('canonical', ['absolute' => TRUE])->toString() : '/locations',
      ],
    ];
    $form['#attached']['drupalSettings']['asset_tree'] = $tree;
    $form['#attached']['drupalSettings']['asset_parent'] = !empty($asset) ? $asset->uuid() : '';
    $form['#attached']['drupalSettings']['asset_parent_type'] = !empty($asset) ? $asset->bundle() : '';

    // Return the form.
    return $form;
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

    // Query unarchived location assets.
    $storage = $this->entityTypeManager->getStorage('asset');
    $query = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('is_location', TRUE)
      ->condition('status', 'archived', '!=')
      ->sort('name');

    // Limit to a specific parent or no parent.
    if ($asset) {
      $query->condition('parent', $asset->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }

    // Query and return assets.
    $asset_ids = $query->execute();
    if (empty($asset_ids)) {
      return [];
    }
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $storage->loadMultiple($asset_ids);
    return $assets;
  }

}
