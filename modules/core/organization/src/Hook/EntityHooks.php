<?php

declare(strict_types=1);

namespace Drupal\organization\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\organization\Entity\OrganizationInterface;
use Drupal\organization\Event\OrganizationEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Entity hook implementations for organization.
 */
class EntityHooks {

  public function __construct(
    #[Autowire(service: 'event_dispatcher')]
    protected EventDispatcherInterface $eventDispatcher,
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_presave().
   */
  #[Hook('organization_presave')]
  public function organizationPresave(OrganizationInterface $organization) {

    // Dispatch an event on organization presave.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new OrganizationEvent($organization);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, OrganizationEvent::PRESAVE);
  }

  /**
   * Implements hook_ENTITY_TYPE_insert().
   */
  #[Hook('organization_insert')]
  public function organizationInsert(OrganizationInterface $organization) {

    // Dispatch an event on organization insert.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new OrganizationEvent($organization);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, OrganizationEvent::INSERT);
  }

  /**
   * Implements hook_ENTITY_TYPE_update().
   */
  #[Hook('organization_update')]
  public function organizationUpdate(OrganizationInterface $organization) {

    // Dispatch an event on organization update.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new OrganizationEvent($organization);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, OrganizationEvent::UPDATE);
  }

  /**
   * Implements hook_ENTITY_TYPE_delete().
   */
  #[Hook('organization_delete')]
  public function organizationDelete(OrganizationInterface $organization) {

    // Dispatch an event on organization delete.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new OrganizationEvent($organization);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, OrganizationEvent::DELETE);
  }

}
