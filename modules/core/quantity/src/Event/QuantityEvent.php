<?php

declare(strict_types=1);

namespace Drupal\quantity\Event;

use Drupal\quantity\Entity\QuantityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event that is fired by quantity entity operations.
 *
 * @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
 *   Use Drupal core entity hooks instead.
 * @see https://www.drupal.org/node/3576637
 */
class QuantityEvent extends Event {

  const PRESAVE = 'quantity_presave';
  const DELETE = 'quantity_delete';

  public function __construct(
    public QuantityInterface $quantity,
  ) {}

}
