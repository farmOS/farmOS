<?php

namespace Drupal\farm_ui_location\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines actions to add location assets on locations page.
 */
class AddLocationAssetAction extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a FarmActions object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Get asset types that are locations by default.
    $asset_storage = $this->entityTypeManager->getStorage('asset_type');
    $asset_type_ids = $asset_storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('status', TRUE)
      ->exists('third_party_settings.farm_location')
      ->condition('third_party_settings.farm_location.is_location', TRUE)
      ->execute();

    // Bail if there are no asset types.
    if (empty($asset_type_ids)) {
      return parent::getDerivativeDefinitions($base_plugin_definition);
    }

    // Add links to asset/add/type for each asset type.
    $asset_label = $this->entityTypeManager->getDefinition('asset')->getLabel();
    foreach ($asset_storage->loadMultiple($asset_type_ids) as $asset_type_id => $asset_type) {
      $this->derivatives[$asset_type_id] = $base_plugin_definition;
      $this->derivatives[$asset_type_id]['title'] = $this->t('Add @bundle @entity_type', ['@bundle' => $asset_type->label(), '@entity_type' => $asset_label]);
      $this->derivatives[$asset_type_id]['route_name'] = 'entity.asset.add_form';
      $this->derivatives[$asset_type_id]['route_parameters'] = ['asset_type' => $asset_type_id];
      $this->derivatives[$asset_type_id]['appears_on'][] = 'farm.locations';
      $this->derivatives[$asset_type_id]['cache_tags'] = ['config:asset_type_list'];
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
