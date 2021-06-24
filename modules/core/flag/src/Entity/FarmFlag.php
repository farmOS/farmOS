<?php

namespace Drupal\farm_flag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmFlag entity.
 *
 * @ConfigEntityType(
 *   id = "flag",
 *   label = @Translation("Flag"),
 *   label_collection = @Translation("Flags"),
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
class FarmFlag extends ConfigEntityBase implements FarmFlagInterface {

  /**
   * The flag ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The flag label.
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
