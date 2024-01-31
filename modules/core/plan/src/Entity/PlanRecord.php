<?php

namespace Drupal\plan\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Plan record relationship entity.
 *
 * This entity type can be used to create relationships between a plan and other
 * record(s) along with additional metadata fields to describe the relationship.
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
 *   handlers = {
 *     "access" = "Drupal\plan\Access\PlanRecordAccess",
 *     "form" = {
 *        "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *      },
 *     "route_provider" = {
 *        "default" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *      },
 *   },
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
 *   links = {
 *      "edit-form" = "/plan/record/{plan_record}/edit",
 *    },
 * )
 */
class PlanRecord extends ContentEntityBase implements PlanRecordInterface {

  /**
   * {@inheritdoc}
   */
  public function getBundleLabel() {
    /** @var \Drupal\plan\Entity\PlanRecordTypeInterface $type */
    $type = \Drupal::entityTypeManager()
      ->getStorage('plan_record_type')
      ->load($this->bundle());
    return $type->label();
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['plan'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Plan'))
      ->setDescription(t('Associate this plan record relationship with a plan entity.'))
      ->setTranslatable(FALSE)
      ->setCardinality(1)
      ->setSetting('target_type', 'plan')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference',
        'weight' => 12,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlan(): ?PlanInterface {
    return $this->get('plan')->first()?->entity;
  }

}
