<?php

namespace Drupal\farm_quick\Traits;

use Drupal\fraction\Fraction;
use Drupal\quantity\Entity\Quantity;

/**
 * Provides methods for working with quantities.
 */
trait QuickQuantityTrait {

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
  public function createQuantity(array $values = []) {

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
    if (!empty($values['units'])) {
      $term = $this->createOrLoadTerm($values['units'], 'unit');
      $values['units'] = $term;
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
