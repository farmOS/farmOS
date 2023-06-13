<?php

namespace Drupal\asset\Event;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\EventDispatcher\Event;

/**
 * Event that is fired by asset save, delete and clone operations.
 */
class AssetEvent extends Event {

  const PRESAVE = 'asset_presave';
  const INSERT = 'asset_insert';
  const UPDATE = 'asset_update';
  const DELETE = 'asset_delete';

  /**
   * The Asset entity.
   *
   * @var \Drupal\asset\Entity\AssetInterface
   */
  public AssetInterface $asset;

  /**
   * Constructs the object.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   */
  public function __construct(AssetInterface $asset) {
    $this->asset = $asset;
  }

}
