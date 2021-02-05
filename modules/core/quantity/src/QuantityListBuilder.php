<?php

namespace Drupal\quantity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of Quantity entities.
 *
 * @ingroup quantity
 */
class QuantityListBuilder extends BulkFormEntityListBuilder {

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
    /** @var \Drupal\quantity\Entity\QuantityInterface $entity */
    $row['id'] = ['#markup' => $entity->id()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    return $row + parent::buildRow($entity);
  }

}
