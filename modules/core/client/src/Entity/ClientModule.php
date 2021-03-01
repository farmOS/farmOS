<?php

namespace Drupal\farm_client\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the ClientModule entity.
 *
 * @ConfigEntityType(
 *   id = "client_module",
 *   label = @Translation("Client module"),
 *   label_collection = @Translation("Client modules"),
 *   handlers = { },
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "library",
 *   },
 * )
 *
 * @ingroup farm
 */
class ClientModule extends ConfigEntityBase implements ClientModuleInterface {

  /**
   * The client module ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The client module label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this client module.
   *
   * @var string
   */
  protected $description;

  /**
   * The client module library name.
   *
   * @var string
   */
  protected $library;

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
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function getLibrary() {
    return $this->library;
  }

}
