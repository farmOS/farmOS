<?php

namespace Drupal\quantity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the quantity type entity.
 *
 * @ConfigEntityType(
 *   id = "quantity_type",
 *   label = @Translation("Quantity type"),
 *   label_collection = @Translation("Quantity types"),
 *   label_singular = @Translation("Quantity type"),
 *   label_plural = @Translation("Quantity types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count quantity type",
 *     plural = "@count quantity types",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\BundleEntityAccessControlHandler",
 *   },
 *   config_prefix = "type",
 *   bundle_of = "quantity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "default_measure",
 *     "new_revision",
 *   }
 * )
 */
class QuantityType extends ConfigEntityBundleBase implements QuantityTypeInterface {

  /**
   * The quantity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The quantity type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this quantity type.
   *
   * @var string
   */
  protected $description;

  /**
   * The default measure of this quantity type.
   *
   * @var string|null
   */
  protected $default_measure;

  /**
   * Default value of the 'Create new revision' checkbox of the quantity type.
   *
   * @var bool
   */
  protected $new_revision = TRUE;

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
  public function getDefaultMeasure() {
    return $this->default_measure;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldCreateNewRevision() {
    return $this->new_revision;
  }

  /**
   * {@inheritdoc}
   */
  public function setNewRevision($new_revision) {
    return $this->set('new_revision', $new_revision);
  }

}
