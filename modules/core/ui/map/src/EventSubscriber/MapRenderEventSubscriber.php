<?php

namespace Drupal\farm_ui_map\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_map\Event\MapRenderEvent;
use Drupal\farm_map\LayerStyleLoaderInterface;
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
   * The layer style loader service.
   *
   * @var \Drupal\farm_map\layerStyleLoader
   */
  protected $layerStyleLoader;

  /**
   * MapRenderEventSubscriber Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\farm_map\LayerStyleLoaderInterface $layer_style_loader
   *   The layer style loader service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LayerStyleLoaderInterface $layer_style_loader) {
    $this->assetTypes = $entity_type_manager->getStorage('asset_type')->loadMultiple();
    $this->layerStyleLoader = $layer_style_loader;
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

    // Add behaviors/settings to default and geofield maps.
    if (in_array($event->getmapType()->id(), ['default', 'geofield'])) {

      // Add "All locations" layers.
      $event->addBehavior('asset_type_layers');
      $settings[$event->getMapTargetId()]['asset_type_layers']['all_locations'] = [
        'label' => $this->t('All locations'),
        'filters' => [
          'is_location' => 1,
        ],
        'color' => 'grey',
        'zoom' => TRUE,
      ];
      $event->addSettings($settings);

      // Prevent zooming to the "All locations" layer if WKT is provided.
      if (!empty($event->element['#map_settings']['wkt'])) {
        $settings[$event->getMapTargetId()]['asset_type_layers']['all_locations']['zoom'] = FALSE;
        $event->addSettings($settings);
      }
    }

    // If the "locations" behavior is added to the map, add layers for each
    // location asset type.
    if (in_array('locations', $event->getMapBehaviors())) {

      $layers = [];

      // Define common layer properties.
      $group = $this->t('Locations');
      $filters = [
        'is_location' => 1,
      ];

      // Add layer for all asset types are locations by default.
      foreach ($this->assetTypes as $type) {

        // Only add a layer if the asset type is a location by default.
        if ($type->getThirdPartySetting('farm_location', 'is_location', FALSE)) {

          // Load the map layer style.
          /** @var \Drupal\farm_map\Entity\LayerStyleInterface $layer_style */
          $layer_style = $this->layerStyleLoader->load(['asset_type' => $type->id()]);
          if (!is_null($layer_style)) {
            $color = $layer_style->get('color');
          }

          // Add layer for the asset type.
          $layers[$type->id()] = [
            'group' => $group,
            'label' => $type->label(),
            'asset_type' => $type->id(),
            'filters' => $filters,
            'color' => $color ?? 'orange',
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
