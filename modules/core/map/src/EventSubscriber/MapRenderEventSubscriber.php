<?php

namespace Drupal\farm_map\EventSubscriber;

use Drupal\farm_map\Event\MapRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the MapRenderEvent.
 *
 * Adds default behaviors to maps.
 */
class MapRenderEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MapRenderEvent::EVENT_NAME => 'onMapRender',
    ];
  }

  /**
   * React to the MapRenderEvent.
   *
   * @param \Drupal\farm_map\Event\MapRenderEvent $event
   *   The MapRenderEvent.
   */
  public function onMapRender(MapRenderEvent $event) {

    // Add the map type cache tags.
    $event->addCacheTags($event->getMapType()->getCacheTags());

    // Include map behaviors defined by the map type.
    $map_behaviors = $event->getMapType()->getMapBehaviors();
    foreach ($map_behaviors as $behavior) {
      $event->addBehavior($behavior);
    }

    // Add the WKT behavior if the render element has WKT.
    if (!empty($event->element['#map_settings']['wkt'])) {
      $event->addBehavior('wkt');
    }

    // Add the wkt and geofield behavior to the geofield_widget map.
    if (in_array($event->getMapType()->id(), ['geofield_widget'])) {
      $event->addBehavior('wkt');
      $event->addBehavior('geofield');
    }
  }

}
