<?php

namespace Drupal\farm_material\EventSubscriber;

use Drupal\quantity\Event\QuantityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions when quantity entities are saved/deleted.
 */
class QuantityEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      QuantityEvent::PRESAVE => 'quantityPresave',
    ];
  }

  /**
   * Copy material_type from a material inventory asset to material quantity.
   *
   * @param \Drupal\quantity\Event\QuantityEvent $event
   *   Quantity event.
   */
  public function quantityPresave(QuantityEvent $event) {

    $quantity = $event->quantity;

    // Bail if not a material quantity.
    if ($quantity->bundle() !== 'material') {
      return;
    }

    // Bail if there is no inventory field or if it is empty.
    if (!$quantity->hasField('inventory_asset') || $quantity->get('inventory_asset')->isEmpty()) {
      return;
    }

    // Get the referenced inventory asset.
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = $event->quantity->get('inventory_asset')->referencedEntities();
    $asset = reset($assets);

    // Bail if not a material asset.
    if (empty($asset) || $asset->bundle() !== 'material') {
      return;
    }

    // Copy the material asset material_type field to the material quantity.
    if (!$asset->get('material_type')->isEmpty()) {
      $material_type = $asset->get('material_type')->getValue();
      $quantity->set('material_type', $material_type);
    }
  }

}
