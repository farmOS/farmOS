<?php

namespace Drupal\farm_quick_movement\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\farm_location\Plugin\Field\FieldFormatter\AssetCurrentLocationFormatter;

/**
 * Field formatter for the current location asset field with a move button.
 *
 * @FieldFormatter(
 *   id = "asset_current_location_move",
 *   label = @Translation("Asset current location (with Move button)"),
 *   description = @Translation("Display the label of the referenced entities with a button to move."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class AssetCurrentLocationMoveFormatter extends AssetCurrentLocationFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'move_asset_button' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $elements['move_asset_button'] = [
      '#title' => $this->t('Move asset button'),
      '#description' => $this->t('Include a button to move the asset.'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('move_asset_button'),
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->getSetting('move_asset_button') ? $this->t('Include move asset button') : $this->t('No move asset button');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    // Build labels in parent.
    $elements = parent::viewElements($items, $langcode);

    // Get the asset.
    $asset = $items->getEntity();

    // If the asset is fixed don't render additional information.
    if ($asset->get('is_fixed')->value) {
      return $elements;
    }

    // If there are no current locations only render if configured to.
    if (empty($elements) && !$this->getSetting('render_without_location')) {
      return $elements;
    }

    // Add the move asset button if configured.
    if ($this->getSetting('move_asset_button')) {

      // Append a "Move asset" link.
      $options = [
        'query' => [
          'asset' => $asset->id(),
          'destination' => $asset->toUrl()->toString(),
        ],
      ];
      $elements[] = [
        '#type' => 'link',
        '#title' => $this->t('Move asset'),
        '#url' => Url::fromRoute('farm.quick.movement', [], $options),
        '#attributes' => [
          'class' => ['button', 'button--small'],
        ],
      ];
    }
    return $elements;
  }

}
