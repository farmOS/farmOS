<?php

namespace Drupal\farm_id_tag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FarmIDTagType entity.
 *
 * @ConfigEntityType(
 *   id = "tag_type",
 *   label = @Translation("ID tag type"),
 *   label_collection = @Translation("ID tag types"),
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
class FarmIDTagType extends ConfigEntityBase implements FarmIDTagTypeInterface {

  /**
   * The ID tag type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The ID tag type label.
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
