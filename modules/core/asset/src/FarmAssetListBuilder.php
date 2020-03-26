<?php

namespace Drupal\farm_asset;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of farm_asset entities.
 *
 * @ingroup farm_asset
 */
class FarmAssetListBuilder extends BulkFormEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Farm Asset ID');
    $header['label'] = $this->t('Label');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\farm_asset\Entity\FarmAssetInterface */
    $row['id'] = ['#markup' => $entity->id()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    $row['type'] = ['#markup' => $entity->getBundleLabel()];
    return $row + parent::buildRow($entity);
  }

}
