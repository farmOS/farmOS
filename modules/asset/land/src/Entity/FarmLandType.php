<?php

namespace Drupal\farm_land\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmLandType entity.
 *
 * @ConfigEntityType(
 *   id = "land_type",
 *   label = @Translation("Land type"),
 *   label_collection = @Translation("Land types"),
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
class FarmLandType extends ConfigEntityBase implements FarmLandTypeInterface {

  /**
   * The land type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The land type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The land type color.
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
