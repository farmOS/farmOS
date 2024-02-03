<?php

namespace Drupal\farm_structure\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_map\Event\MapRenderEvent;
use Drupal\farm_map\LayerStyleLoaderInterface;
use Drupal\farm_structure\Entity\FarmStructureType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for the MapRenderEvent.
 */
class MapRenderEventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * Structure asset type.
   *
   * @var \Drupal\asset\Entity\AssetTypeInterface
   */
  protected $structureAssetType;

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
    $this->structureAssetType = $entity_type_manager->getStorage('asset_type')->load('structure');
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
    // structure type.
    if (in_array('locations', $event->getMapBehaviors())) {
      $layers = [];

      // Define the parent group.
      $group = $this->t('Locations');

      // Create a layer group for the asset type.
      $layers['structure'] = [
        'group' => $group,
        'label' => $this->structureAssetType->label(),
        'is_group' => TRUE,
      ];

      // Load structure types.
      $structure_types = FarmStructureType::loadMultiple();

      // Create a layer for each sub-type.
      foreach ($structure_types as $structure_type) {
        /** @var \Drupal\farm_map\Entity\LayerStyleInterface $layer_style */
        $conditions = [
          'asset_type' => 'structure',
          'structure_type' => $structure_type->id(),
        ];
        $layer_style = $this->layerStyleLoader->load($conditions);
        if (!empty($layer_style)) {
          $color = $layer_style->get('color');
        }
        $layers['structure_' . $structure_type->id()] = [
          'group' => $this->structureAssetType->label(),
          'label' => $structure_type->label(),
          'asset_type' => 'structure',
          'filters' => ['structure_type_value[]' => $structure_type->id()],
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
