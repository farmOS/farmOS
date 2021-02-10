<?php

namespace Drupal\farm_map\EventSubscriber;

use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_map\Event\MapRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the MapRenderEvent.
 *
 * Adds the wkt and geofield behaviors to necessary maps.
 */
class MapRenderEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * MapRenderEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info service.
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_type_bundle_info) {
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
  }

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

      // Prevent zooming to the "All locations" layer if WKT is provided.
      $settings[$event->getMapTargetId()]['asset_type_layers']['all_locations']['zoom'] = FALSE;
      $event->addSettings($settings);
    }

    // Add the wkt and geofield behavior to the geofield_widget map.
    if (in_array($event->getMapType()->id(), ['geofield_widget'])) {
      $event->addBehavior('wkt');
      $event->addBehavior('geofield');
    }

    // Add asset layers to dashbaord map.
    if ($event->getmapType()->id() == 'dashboard') {

      $layers = [];

      // Define common layer properties.
      $group = $this->t('Location assets');
      $filters = [
        'is_location' => 1,
      ];

      // @todo Make these types configurable.
      $dashboard_asset_types = ['land', 'structure', 'water'];
      $asset_types = $this->entityTypeBundleInfo->getBundleInfo('asset');
      foreach ($dashboard_asset_types as $type) {

        // Add layer for each asset type that exists.
        if (isset($asset_types[$type])) {

          // Add layer for the asset type.
          $layers[$type] = [
            'group' => $group,
            'label' => $asset_types[$type]['label'],
            'asset_type' => $type,
            'filters' => $filters,
            // @todo Color each asset type differently.
            // This was previously provided with hook_farm_area_type_info.
            'color' => 'orange',
            'zoom' => TRUE,
          ];
        }
      }

      // Add the asset_type_layers behavior.
      $event->addBehavior('asset_type_layers');

      // Add map specific settings.
      $settings[$event->getMapTargetId()]['asset_type_layers'] = $layers;
      $event->addSettings($settings);
    }
  }

}
