<?php

namespace Drupal\quantity\Event;

use Drupal\quantity\Entity\QuantityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event that is fired by hook_quantity_OPERATION().
 */
class QuantityEvent extends Event {

  const PRESAVE = 'quantity_presave';
  const DELETE = 'quantity_delete';

  /**
   * The Quantity entity.
   *
   * @var \Drupal\quantity\Entity\QuantityInterface
   */
  public QuantityInterface $quantity;

  /**
   * Constructs the object.
   *
   * @param \Drupal\quantity\Entity\QuantityInterface $quantity
   *   The Quantity entity.
   */
  public function __construct(QuantityInterface $quantity) {
    $this->quantity = $quantity;
  }

}
