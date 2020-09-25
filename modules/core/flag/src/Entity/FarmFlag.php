<?php

namespace Drupal\farm_flag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmFlag entity.
 *
 * @ConfigEntityType(
 *   id = "farm_flag",
 *   label = @Translation("Flag"),
 *   label_collection = @Translation("Flags"),
 *   handlers = { },
 *   config_prefix = "farm_flag",
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
