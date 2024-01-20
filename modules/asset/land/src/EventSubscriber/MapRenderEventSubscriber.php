<?php

namespace Drupal\farm_land\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_land\Entity\FarmLandType;
use Drupal\farm_map\Event\MapRenderEvent;
use Drupal\farm_map\LayerStyleLoaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the MapRenderEvent.
 */
class MapRenderEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * Land asset type.
   *
   * @var \Drupal\asset\Entity\AssetTypeInterface
   */
  protected $landAssetType;

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
    $this->landAssetType = $entity_type_manager->getStorage('asset_type')->load('land');
    $this->layerStyleLoader = $layer_style_loader;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MapRenderEvent::EVENT_NAME => ['onMapRender', -100],
    ];
  }

  /**
   * React to the MapRenderEvent.
   *
   * @param \Drupal\farm_map\Event\MapRenderEvent $event
   *   The MapRenderEvent.
   */
  public function onMapRender(MapRenderEvent $event) {

    // If the "locations" behavior is added to the map, add layers for each
    // land type.
    if (in_array('locations', $event->getMapBehaviors())) {
      $layers = [];

      // Define the parent group.
      $group = $this->t('Locations');

      // Create a layer group for the asset type.
      $layers['land'] = [
        'group' => $group,
        'label' => $this->landAssetType->label(),
        'is_group' => TRUE,
      ];

      // Load land types.
      $land_types = FarmLandType::loadMultiple();

      // Create a layer for each sub-type.
      foreach ($land_types as $land_type) {
        /** @var \Drupal\farm_map\Entity\LayerStyleInterface $layer_style */
        $conditions = [
          'asset_type' => 'land',
          'land_type' => $land_type->id(),
        ];
        $layer_style = $this->layerStyleLoader->load($conditions);
        if (!empty($layer_style)) {
          $color = $layer_style->get('color');
        }
        $layers['land_' . $land_type->id()] = [
          'group' => $this->landAssetType->label(),
          'label' => $land_type->label(),
          'asset_type' => 'land',
          'filters' => ['land_type_value[]' => $land_type->id()],
          'color' => $color ?? 'orange',
          'zoom' => TRUE,
        ];
      }

      // Add layers to the map settings.
      $settings[$event->getMapTargetId()]['asset_type_layers'] = $layers;
      $event->addSettings($settings);
    }
  }

}
