<?php

declare(strict_types=1);

namespace Drupal\organization\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\organization\Entity\OrganizationInterface;

/**
 * Event that is fired by organization entity operations.
 *
 * @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
 *   Use Drupal core entity hooks instead.
 * @see https://www.drupal.org/node/3576637
 */
class OrganizationEvent extends Event {

  const PRESAVE = 'organization_presave';
  const INSERT = 'organization_insert';
  const UPDATE = 'organization_update';
  const DELETE = 'organization_delete';

  public function __construct(
    protected OrganizationInterface $organization,
  ) {}

}
