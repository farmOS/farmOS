<?php

namespace Drupal\farm_location\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Form\FormStateInterface;

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
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->getSetting('render_without_location') ? $this->t('Render without current location') : $this->t('Do not render without current location');
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

    // Add N/A if there are no current locations.
    if (empty($elements)) {

      // Render N/A if configured.
      $elements[] = ['#markup' => 'N/A'];
    }

    return $elements;
  }

}
