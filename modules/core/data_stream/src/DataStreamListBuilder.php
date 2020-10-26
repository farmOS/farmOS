<?php

namespace Drupal\data_stream;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity\BulkFormEntityListBuilder;

/**
 * Defines a class to build a listing of data stream entities.
 */
class DataStreamListBuilder extends BulkFormEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['uuid'] = $this->t('UUID');
    $header['label'] = $this->t('Name');
    $header['type'] = $this->t('Type');
    $header['public'] = $this->t('Public');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\data_stream\Entity\DataStreamInterface $entity */
    $row['id'] = ['#markup' => $entity->id()];
    $row['uuid'] = ['#markup' => $entity->uuid()];
    $row['name'] = $entity->toLink($entity->label(), 'canonical')->toRenderable();
    $row['type'] = ['#markup' => $entity->getBundleLabel()];
    $row['public'] = ['#markup' => $entity->isPublic()];
    return $row + parent::buildRow($entity);
  }

}
