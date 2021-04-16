<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the LayerStyle config entity.
 *
 * @ConfigEntityType(
 *   id = "layer_style",
 *   label = @Translation("Layer style"),
 *   label_collection = @Translation("Layer styles"),
 *   handlers = { },
 *   admin_permission = "administer farm map",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   config_export = {
 *     "id",
 *     "color",
 *     "conditions",
 *   },
 * )
 *
 * @ingroup farm
 */
class LayerStyle extends ConfigEntityBase implements LayerStyleInterface {

  /**
   * The layer style ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The layer style conditions.
   *
   * @var mixed|null
   */
  protected $conditions;

  /**
   * {@inheritdoc}
   */
  public function getConditions() {
    return $this->conditions;
  }

}
