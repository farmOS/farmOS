<?php

namespace Drupal\plan;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of plan entities.
 *
 * @ingroup plan
 */
class PlanListBuilder extends BulkFormEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Plan ID');
    $header['label'] = $this->t('Label');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\plan\Entity\PlanInterface $entity */
    $row['id'] = ['#markup' => $entity->id()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    $row['type'] = ['#markup' => $entity->getBundleLabel()];
    return $row + parent::buildRow($entity);
  }

}
