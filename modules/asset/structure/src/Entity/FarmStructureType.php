<?php

namespace Drupal\farm_structure\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmStructureType entity.
 *
 * @ConfigEntityType(
 *   id = "structure_type",
 *   label = @Translation("Structure type"),
 *   label_collection = @Translation("Structure types"),
 *   handlers = {
 *     "access" = "\Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "\Drupal\entity\EntityPermissionProvider",
 *   },
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
class FarmStructureType extends ConfigEntityBase implements FarmStructureTypeInterface {

  /**
   * The structure type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The structure type label.
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
