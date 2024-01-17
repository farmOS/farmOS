<?php

namespace Drupal\farm_ui_location\Form;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
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

    // If the asset does not have child locations, forbid access.
    if (empty($this->getLocations($asset))) {
      return AccessResult::forbidden();
    }

    // Allow access if the asset has child locations.
    return AccessResult::allowedIf($asset->access('view', $account));
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

    // If no asset was specified, show a map of all locations.
    if (is_null($asset)) {
      $form['map'] = [
        '#type' => 'farm_map',
        '#map_type' => 'locations',
      ];
    }

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

    // Create a hidden field to store hierarchy changes recorded client-side.
    $form['changes'] = [
      '#type' => 'hidden',
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
        'asset_id' => !empty($asset) ? $asset->id() : '',
        'text' => !empty($asset) ? $asset->label() : $this->t('All locations'),
        'children' => !empty($asset) ? $this->buildTree($asset) : $this->buildTree(),
        'url' => !empty($asset) ? $asset->toUrl('canonical', ['absolute' => TRUE])->toString() : Url::fromRoute('farm.locations', [], ['absolute' => TRUE])->toString(),
      ],
    ];
    $form['#attached']['drupalSettings']['asset_tree'] = $tree;
    $form['#attached']['drupalSettings']['asset_parent'] = !empty($asset) ? $asset->id() : '';

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
          'asset_id' => $location->id(),
          'text' => $location->label(),
          'children' => $this->buildTree($location),
          'url' => $location->toUrl('canonical', ['absolute' => TRUE])->toString(),
        ];
        $element['original_parent'] = $asset ? $asset->id() : '';
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
      ->condition('status', 'archived', '!=');

    // Limit to a specific parent or no parent.
    if ($asset) {
      $query->condition('parent', $asset->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }

    // Query and load the assets.
    $asset_ids = $query->execute();
    if (empty($asset_ids)) {
      return [];
    }
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $storage->loadMultiple($asset_ids);

    // Filter out assets that the user cannot view.
    $assets = array_filter($assets, function ($asset) {
      return $asset->access('view');
    });

    // Sort assets by name, using natural sort algorithm.
    usort($assets, function ($a, $b) {
      return strnatcmp($a->label(), $b->label());
    });

    return $assets;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Only process the form if the "Save" button was clicked.
    if ($form_state->getTriggeringElement()['#id'] != 'edit-save') {
      return;
    }

    // Load hierarchy changes. If there are none, do nothing.
    $changes = Json::decode($form_state->getValue('changes'));
    if (empty($changes)) {
      $this->messenger()->addStatus($this->t('No changes were made.'));
      return;
    }

    // Get asset storage.
    $storage = $this->entityTypeManager->getStorage('asset');

    // Maintain a list of assets that need to be saved.
    $save_assets = [];

    // Maintain a list of assets that were not editable by the user.
    $restricted_assets = [];

    // Iterate through the changes.
    foreach ($changes as $change) {

      // Load the asset.
      $asset = $storage->load($change['asset_id']);

      // If the user does not have permission to edit the asset, count it so
      // that we can add a warning message later, and skip it.
      if (!$asset->access('edit')) {
        $restricted_assets[] = $asset;
        continue;
      }

      // Remove the original parent.
      if (!empty($asset->get('parent'))) {
        /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $parent */
        foreach ($asset->get('parent') as $delta => $parent) {
          $parent_id = $parent->getValue()['target_id'];
          if ($change['original_parent'] == $parent_id) {
            unset($asset->get('parent')[$delta]);
            if (!array_key_exists($asset->id(), $save_assets)) {
              $save_assets[$asset->id()] = $asset;
            }
          }
        }
      }

      // Add the new parent, if applicable.
      if (!empty($change['new_parent'])) {
        $asset->get('parent')[] = ['target_id' => $change['new_parent']];
        if (!array_key_exists($asset->id(), $save_assets)) {
          $save_assets[$asset->id()] = $asset;
        }
      }
    }

    // Save assets with a revision message.
    /** @var \Drupal\asset\Entity\AssetInterface[] $save_assets */
    foreach ($save_assets as $asset) {
      $message = $this->t('Parents removed via the Locations drag and drop editor.');
      $parent_names = [];
      foreach ($asset->get('parent') as $parent) {
        $parent_names[] = $storage->load($parent->getValue()['target_id'])->label();
      }
      if (!empty($parent_names)) {
        $message = $this->t('Parents changed to %parents via the Locations drag and drop editor.', ['%parents' => implode(', ', $parent_names)]);
      }
      $asset->setNewRevision(TRUE);
      $asset->setRevisionLogMessage($message);
      $asset->save();
    }

    // Show a summary of the results.
    $message = $this->formatPlural(count($save_assets), 'Updated the parent hierarchy of %count asset.', 'Updated the parent hierarchy of %count assets.', ['%count' => count($save_assets)]);
    $this->messenger()->addStatus($message);

    // If any edits were restricted, show a warning.
    if ($restricted_assets) {
      $message = $this->formatPlural(count($restricted_assets), '%count asset could not be changed because you do not have permission.', '%count assets could not be changed because you do not have permission.', ['%count' => count($restricted_assets)]);
      $this->messenger()->addWarning($message);
    }
  }

}
