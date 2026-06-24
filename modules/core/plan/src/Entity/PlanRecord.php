<?php

declare(strict_types=1);

namespace Drupal\plan\Entity;

use Drupal\Core\Entity\Attribute\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\plan\Access\PlanRecordAccess;

/**
 * Defines the Plan record relationship entity.
 *
 * This entity type can be used to create relationships between a plan and other
 * record(s) along with additional metadata fields to describe the relationship.
 */
#[ContentEntityType(
  id: 'plan_record',
  label: new TranslatableMarkup('Plan record relationship'),
  label_collection: new TranslatableMarkup('Plan record relationships'),
  label_singular: new TranslatableMarkup('plan record relationship'),
  label_plural: new TranslatableMarkup('plan record relationships'),
  entity_keys: [
    'id' => 'id',
    'uuid' => 'uuid',
    'label' => 'uuid',
    'bundle' => 'type',
  ],
  handlers: [
    'access' => PlanRecordAccess::class,
    'form' => [
      'edit' => ContentEntityForm::class,
    ],
    'route_provider' => [
      'default' => AdminHtmlRouteProvider::class,
    ],
  ],
  links: [
    'edit-form' => '/plan/record/{plan_record}/edit',
  ],
  bundle_entity_type: 'plan_record_type',
  bundle_label: new TranslatableMarkup('Plan record relationship type'),
  base_table: 'plan_record',
  label_count: [
    'singular' => '@count plan record relationship',
    'plural' => '@count plan record relationships',
  ],
  common_reference_target: TRUE,
)]
class PlanRecord extends ContentEntityBase implements PlanRecordInterface {

  /**
   * {@inheritdoc}
   */
  public function getBundleLabel() {
    /** @var \Drupal\plan\Entity\PlanRecordTypeInterface $type */
    $type = $this->entityTypeManager()
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
      ->setRequired(TRUE)
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
    if (!$this->get('plan')->isEmpty()) {
      return $this->get('plan')->referencedEntities()[0];
    }
    return NULL;
  }

}
