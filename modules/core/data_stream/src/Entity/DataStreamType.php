<?php

namespace Drupal\data_stream\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Data Stream type entity.
 *
 * @ConfigEntityType(
 *   id = "data_stream_type",
 *   label = @Translation("Data stream type"),
 *   label_collection = @Translation("Data stream types"),
 *   label_singular = @Translation("Data stream type"),
 *   label_plural = @Translation("Data stream types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count data stream type",
 *     plural = "@count data stream types",
 *   ),
 *   handlers = {
 *   "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer data stream types",
 *   config_prefix = "type",
 *   bundle_of = "data_stream",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/data_stream_type/add",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *   }
 * )
 */
class DataStreamType extends ConfigEntityBundleBase implements DataStreamTypeInterface {

  /**
   * The Data Stream type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Data Stream type label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of this data stream type.
   *
   * @var string
   */
  protected $description;

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

}
