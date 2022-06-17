<?php

namespace Drupal\farm_location\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Field formatter for the current location asset field.
 *
 * @FieldFormatter(
 *   id = "asset_current_location",
 *   label = @Translation("Asset current location"),
 *   description = @Translation("Display the label of the referenced entities."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class AssetCurrentLocationFormatter extends EntityReferenceLabelFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'render_without_location' => FALSE,
      'move_asset_button' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $elements['render_without_location'] = [
      '#title' => $this->t('Render without location'),
      '#description' => $this->t('Include this field when the asset has no current location.'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('render_without_location'),
    ];
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
    $summary[] = $this->getSetting('render_without_location') ? $this->t('Render without current location') : $this->t('Do not render without current location');
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

    // If there are no current locations only render if configured to.
    if (empty($elements) && !$this->getSetting('render_without_location')) {
      return $elements;
    }

    // Add N/A if there are no current locations.
    if (empty($elements)) {

      // Render N/A if configured.
      $elements[] = ['#markup' => 'N/A'];
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
        '#url' => Url::fromRoute('farm_location.asset_move_action_form', [], $options),
        '#attributes' => [
          'class' => ['button', 'button--small'],
        ],
      ];
    }
    return $elements;
  }

}
