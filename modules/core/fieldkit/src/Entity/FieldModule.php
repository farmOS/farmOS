<?php

namespace Drupal\farm_fieldkit\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FieldModule entity.
 *
 * @ConfigEntityType(
 *   id = "field_module",
 *   label = @Translation("Field module"),
 *   label_collection = @Translation("Field modules"),
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
 *     "description",
 *     "library",
 *   },
 * )
 *
 * @ingroup farm
 */
class FieldModule extends ConfigEntityBase implements FieldModuleInterface {

  /**
   * The field module ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The field module label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this field module.
   *
   * @var string
   */
  protected $description;

  /**
   * The field module library name.
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
