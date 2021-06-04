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
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

}
