<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the MapBehavior config entity.
 *
 * @ConfigEntityType(
 *   id = "map_behavior",
 *   label = @Translation("Map behavior"),
 *   label_collection = @Translation("Map behaviors"),
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
 *     "library",
 *     "settings",
 *   },
 * )
 *
 * @ingroup farm
 */
class MapBehavior extends ConfigEntityBase implements MapBehaviorInterface {

  /**
   * The map behavior ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The map behavior label.
   *
   * @var string
   */
  protected $label;

  /**
   * The behavior library name.
   *
   * @var string
   */
  protected $library;

  /**
   * A brief description of this map behavior.
   *
   * @var string
   */
  protected $description;

  /**
   * The behavior settings.
   *
   * @var mixed|null
   */
  protected $settings;

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
  public function getLibrary() {
    return $this->library;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettings() {
    return $this->settings;
  }

}
