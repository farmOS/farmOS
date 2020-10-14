<?php

namespace Drupal\farm_lab_test\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmLabTestType entity.
 *
 * @ConfigEntityType(
 *   id = "lab_test_type",
 *   label = @Translation("Lab test type"),
 *   label_collection = @Translation("Lab test type"),
 *   handlers = { },
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   },
 * )
 *
 * @ingroup farm
 */
class FarmLabTestType extends ConfigEntityBase implements FarmLabTestTypeInterface {

  /**
   * The lab test type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The lab test type label.
   *
   * @var string
   */
  protected $label;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

}
