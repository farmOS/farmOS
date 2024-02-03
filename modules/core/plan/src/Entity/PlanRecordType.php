<?php

namespace Drupal\plan\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Plan record relationship type entity.
 *
 * @ConfigEntityType(
 *   id = "plan_record_type",
 *   label = @Translation("Plan record relationship type"),
 *   label_collection = @Translation("Plan record relationship types"),
 *   label_singular = @Translation("Plan record relationship type"),
 *   label_plural = @Translation("plan record relationship types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count plan record relationship type",
 *     plural = "@count plan record relationship types",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\BundleEntityAccessControlHandler",
 *   },
 *   config_prefix = "record.type",
 *   bundle_of = "plan_record",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *   }
 * )
 */
class PlanRecordType extends ConfigEntityBundleBase implements PlanRecordTypeInterface {

  /**
   * The Plan record relationship type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Plan record relationship type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this plan record relationship type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    return $this->set('description', $description);
  }

}
