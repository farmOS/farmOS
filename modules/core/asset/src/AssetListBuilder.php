<?php

namespace Drupal\asset;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of asset entities.
 *
 * @ingroup asset
 */
class AssetListBuilder extends BulkFormEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Asset ID');
    $header['label'] = $this->t('Label');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\asset\Entity\AssetInterface $entity */
    $row['id'] = ['#markup' => $entity->id()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    $row['type'] = ['#markup' => $entity->getBundleLabel()];
    return $row + parent::buildRow($entity);
  }

}
