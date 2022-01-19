<?php

namespace Drupal\farm_quick\Traits;

use Drupal\fraction\Fraction;
use Drupal\quantity\Entity\Quantity;

/**
 * Provides methods for working with quantities.
 */
trait QuickQuantityTrait {

  use QuickStringTrait;
  use QuickTermTrait;

  /**
   * Create a quantity.
   *
   * @param array $values
   *   An array of values to initialize the quantity with.
   *
   * @return \Drupal\quantity\Entity\QuantityInterface
   *   The quantity entity that was created.
   */
  protected function createQuantity(array $values = []) {

    // Trim the quantity label to 255 characters.
    if (!empty($values['label'])) {
      $values['label'] = $this->trimString($values['label'], 255);
    }

    // If a type isn't set, default to "standard".
    if (empty($values['type'])) {
      $values['type'] = 'standard';
    }

    // Split value into numerator and denominator, if it isn't already.
    if (!empty($values['value']) && !is_array($values['value'])) {
      $fraction = Fraction::createFromDecimal($values['value']);
      $values['value'] = [
        'numerator' => $fraction->getNumerator(),
        'denominator' => $fraction->getDenominator(),
      ];
    }

    // If the units are a term name, create or load the unit taxonomy term.
    if (!empty($values['units']) && is_string($values['units'])) {
      $term = $this->createOrLoadTerm($values['units'], 'unit');
      $values['units'] = $term->id();
    }
    // Else check if a units term ID is provided and use that instead.
    elseif (!empty($values['units_id'])) {
      $values['units'] = $values['units_id'];
      unset($values['units_id']);
    }

    // Start a new quantity entity with the provided values.
    /** @var \Drupal\quantity\Entity\QuantityInterface $quantity */
    $quantity = Quantity::create($values);

    // Save the quantity.
    $quantity->save();

    // Return the quantity entity.
    return $quantity;
  }

}
