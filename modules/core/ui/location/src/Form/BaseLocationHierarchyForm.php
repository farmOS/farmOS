<?php

declare(strict_types=1);

namespace Drupal\farm_ui_location\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\asset\Entity\AssetInterface;
use Drupal\farm_location\AssetLocationInterface;

/**
 * Base form for changing the hierarchy of location assets.
 *
 * @ingroup farm
 */
abstract class BaseLocationHierarchyForm extends FormBase {

  use AutowireTrait;

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AssetLocationInterface $assetLocation,
  ) {}

  /**
   * Helper function to build the location form.
   *
   * @param array $form
   *   Form array to modify.
   * @param string $root_label
   *   Label for the root element in the hierarchy.
   * @param \Drupal\Core\Url $root_url
   *   URL for the root element in the hierarchy.
   * @param array $root_children
   *   Array of children for the root element in the hierarchy.
   * @param string|null $root_id
   *   Optional ID for the root element in the hierarchy.
   *
   * @return array
   *   The form array.
   */
  public function buildLocationForm(array $form, string $root_label, Url $root_url, array $root_children, ?string $root_id = NULL) {

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
    $form['#attached']['drupalSettings']['asset_tree'] = [
      [
        'asset_id' => $root_id,
        'text' => $root_label,
        'children' => $root_children,
        'url' => $root_url->setAbsolute()->toString(),
      ],
    ];
    $form['#attached']['drupalSettings']['asset_parent'] = $root_id;

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
  protected function buildTree(?AssetInterface $asset = NULL): array {
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
  protected function getLocations(?AssetInterface $asset = NULL) {

    // Query and load location assets.
    $asset_ids = $this->getLocationQuery($asset)->execute();
    if (empty($asset_ids)) {
      return [];
    }
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $this->entityTypeManager->getStorage('asset')
      ->loadMultiple($asset_ids);

    // Sort assets by name, using natural sort algorithm.
    usort($assets, function ($a, $b) {
      return strnatcmp($a->label(), $b->label());
    });

    return $assets;
  }

  /**
   * Helper function to build a query for location assets.
   *
   * @param \Drupal\asset\Entity\AssetInterface|null $parent
   *   Optional parent asset.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   The entity query.
   */
  protected function getLocationQuery(?AssetInterface $parent = NULL): QueryInterface {

    // Query unarchived location assets.
    $query = $this->entityTypeManager->getStorage('asset')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('is_location', TRUE)
      ->condition('archived', 0);
    if ($parent) {
      $query->condition('parent', $parent->id());
    }
    else {
      $query->condition('parent', NULL, 'IS NULL');
    }
    return $query;
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

      // If the user does not have permission to update the asset, count it so
      // that we can add a warning message later, and skip it.
      if (!$asset->access('update')) {
        $restricted_assets[] = $asset;
        continue;
      }

      // Remove the original parent.
      if (!$asset->get('parent')->isEmpty()) {
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
        $asset->get('parent')->appendItem($change['new_parent']);
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
      $asset->setRevisionLogMessage($message->render());
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
