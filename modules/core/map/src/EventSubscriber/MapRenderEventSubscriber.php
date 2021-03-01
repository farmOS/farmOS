<?php

namespace Drupal\farm_map\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
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
   * Asset types.
   *
   * @var \Drupal\asset\Entity\AssetTypeInterface[]
   */
  protected $assetTypes;

  /**
   * MapRenderEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->assetTypes = $entity_type_manager->getStorage('asset_type')->loadMultiple();
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

      // Add layer for all asset types are locations by default.
      foreach ($this->assetTypes as $type) {

        // Only add a layer if the asset type is a location by default.
        if ($type->getThirdPartySetting('farm_location', 'is_location', FALSE)) {

          // Add layer for the asset type.
          $layers[$type->id()] = [
            'group' => $group,
            'label' => $type->label(),
            'asset_type' => $type->id(),
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
