<?php

declare(strict_types=1);

namespace Drupal\asset\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\asset\Entity\AssetInterface;
use Drupal\asset\Event\AssetEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Entity hook implementations for asset.
 */
class EntityHooks {

  public function __construct(
    #[Autowire(service: 'event_dispatcher')]
    protected EventDispatcherInterface $eventDispatcher,
  ) {}

  /**
   * Implements hook_ENTITY_TYPE_presave().
   */
  #[Hook('asset_presave')]
  public function assetPresave(AssetInterface $asset) {

    // Dispatch an event on asset presave.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new AssetEvent($asset);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, AssetEvent::PRESAVE);
  }

  /**
   * Implements hook_ENTITY_TYPE_insert().
   */
  #[Hook('asset_insert')]
  public function assetInsert(AssetInterface $asset) {

    // Dispatch an event on asset insert.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new AssetEvent($asset);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, AssetEvent::INSERT);
  }

  /**
   * Implements hook_ENTITY_TYPE_update().
   */
  #[Hook('asset_update')]
  public function assetUpdate(AssetInterface $asset) {

    // Dispatch an event on asset update.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new AssetEvent($asset);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, AssetEvent::UPDATE);
  }

  /**
   * Implements hook_ENTITY_TYPE_delete().
   */
  #[Hook('asset_delete')]
  public function assetDelete(AssetInterface $asset) {

    // Dispatch an event on asset delete.
    // @deprecated in farm:4.1.0 and is removed from farm:5.0.0.
    // Use Drupal core entity hooks instead.
    // @see https://www.drupal.org/node/3576637
    // @phpstan-ignore-next-line
    $event = new AssetEvent($asset);
    // @phpstan-ignore-next-line
    $this->eventDispatcher->dispatch($event, AssetEvent::DELETE);
  }

}
