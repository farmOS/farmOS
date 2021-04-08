<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the MapType config entity.
 *
 * @ConfigEntityType(
 *   id = "map_type",
 *   label = @Translation("Map type"),
 *   label_collection = @Translation("Map types"),
 *   handlers = { },
 *   admin_permission = "administer farm map",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "behaviors",
 *     "options",
 *   },
 * )
 *
 * @ingroup farm
 */
class MapType extends ConfigEntityBase implements MapTypeInterface {

  /**
   * The map type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The map type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this map type.
   *
   * @var string
   */
  protected $description;

  /**
   * Behaviors to add to the map.
   *
   * @var string[]
   */
  protected $behaviors;

  /**
   * The options to pass to farmOS-map.
   *
   * @var mixed|null
   */
  protected $options;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    return $this->set('description', $description);
  }

  /**
   * {@inheritdoc}
   */
  public function getMapBehaviors() {
    return $this->behaviors ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getMapOptions() {
    return $this->options;
  }

}
