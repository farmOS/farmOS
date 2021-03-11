<?php

namespace Drupal\quantity\Form;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\inline_entity_form\Form\EntityInlineForm;
use Drupal\quantity\Entity\QuantityInterface;

/**
 * Render the quantity entity in quantity inline entity forms.
 */
class QuantityInlineForm extends EntityInlineForm {

  use DependencySerializationTrait;

  /**
   * {@inheritdoc}
   */
  public function getTableFields($bundles) {

    // Trick IEF into thinking there are two bundles.
    // This will always render the "Quantity type" field in the table.
    if (count($bundles) == 1) {
      $bundles[] = $bundles[0];
    }

    // Get parent fields.
    $fields = parent::getTableFields($bundles);

    // Remove the label field.
    unset($fields['label']);

    // Add a field that renders the quantity entity.
    $fields['quantity'] = [
      'type' => 'callback',
      'label' => $this->t('Quantity'),
      'weight' => 1,
      'callback' => [$this, 'renderQuantity'],
    ];

    return $fields;
  }

  /**
   * Callback that renders a quantity entity.
   *
   * @param \Drupal\quantity\Entity\QuantityInterface $quantity
   *   The quantity entity.
   * @param array $theme
   *   Theme information.
   *
   * @return array
   *   The quantity render array.
   */
  public function renderQuantity(QuantityInterface $quantity, array $theme) {
    $view_builder = $this->entityTypeManager->getViewBuilder($quantity->getEntityTypeId());
    return $view_builder->view($quantity);
  }

}
