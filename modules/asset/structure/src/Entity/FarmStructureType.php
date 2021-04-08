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
 *   handlers = { },
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "color",
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
   * The structure type color.
   *
   * @var string
   */
  protected $color;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getColor() {
    return $this->color;
  }

}
