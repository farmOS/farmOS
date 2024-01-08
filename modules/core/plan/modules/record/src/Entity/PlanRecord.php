<?php

namespace Drupal\plan_record\Entity;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Defines the Plan record relationship entity.
 *
 * @ContentEntityType(
 *   id = "plan_record",
 *   label = @Translation("Plan record relationship"),
 *   bundle_label = @Translation("Plan record relationship type"),
 *   label_collection = @Translation("Plan record relationships"),
 *   label_singular = @Translation("plan record relationship"),
 *   label_plural = @Translation("plan record relationships"),
 *   label_count = @PluralTranslation(
 *     singular = "@count plan record relationship",
 *     plural = "@count plan record relationships",
 *   ),
 *   base_table = "plan_record",
 *   data_table = "plan_record_data",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "uuid",
 *     "bundle" = "type",
 *   },
 *   bundle_entity_type = "plan_record_type",
 *   common_reference_target = TRUE,
 * )
 */
class PlanRecord extends ContentEntityBase implements PlanRecordInterface {

  /**
   * {@inheritdoc}
   */
  public function getBundleLabel() {
    /** @var \Drupal\plan_record\Entity\PlanRecordTypeInterface $type */
    $type = \Drupal::entityTypeManager()
      ->getStorage('plan_record_type')
      ->load($this->bundle());
    return $type->label();
  }

}
