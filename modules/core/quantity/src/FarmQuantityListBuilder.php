<?php

namespace Drupal\farm_quantity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of Quantity entities.
 *
 * @ingroup farm_quantity
 */
class FarmQuantityListBuilder extends BulkFormEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\log\Entity\LogInterface */
    $row['id'] = ['#markup' => $entity->id()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    return $row + parent::buildRow($entity);
  }

}
