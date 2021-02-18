<?php

namespace Drupal\farm_entity\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the quantity type plugin annotation object.
 *
 * Plugin namespace: Plugin\Quantity\QuantityType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class QuantityType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The quantity type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
