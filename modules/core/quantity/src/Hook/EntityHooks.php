<?php

declare(strict_types=1);

namespace Drupal\quantity\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\quantity\Entity\QuantityInterface;
use Drupal\quantity\Event\QuantityEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Entity hook implementations for quantity.
 */
class EntityHooks {

  public function __construct(
    #[Autowire(service: 'event_dispatcher')]
    protected EventDispatcherInterface $eventDispatcher,
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_presave().
   */
  #[Hook('quantity_presave')]
  public function quantityPresave(QuantityInterface $quantity) {

    // Dispatch an event on quantity presave.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new QuantityEvent($quantity);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, QuantityEvent::PRESAVE);
  }

  /**
   * Implements hook_ENTITY_TYPE_delete().
   */
  #[Hook('quantity_delete')]
  public function quantityDelete(QuantityInterface $quantity) {

    // Dispatch an event on quantity delete.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new QuantityEvent($quantity);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, QuantityEvent::DELETE);
  }

}
