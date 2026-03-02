<?php

declare(strict_types=1);

namespace Drupal\asset\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\asset\Entity\AssetInterface;

/**
 * Event that is fired by asset entity operations.
 *
 * @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
 *   Use Drupal core entity hooks instead.
 * @see https://www.drupal.org/node/3576637
 */
class AssetEvent extends Event {

  const PRESAVE = 'asset_presave';
  const INSERT = 'asset_insert';
  const UPDATE = 'asset_update';
  const DELETE = 'asset_delete';

  public function __construct(
    public AssetInterface $asset,
  ) {}

}
