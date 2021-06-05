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
 *     "bundles",
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
   * The bundles that this tag type applies to.
   *
   * @var array
   */
  protected $bundles;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundles() {
    return $this->bundles;
  }

}
