<?php

namespace Drupal\farm_entity\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the asset type plugin annotation object.
 *
 * Plugin namespace: Plugin\Asset\AssetType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class AssetType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The asset type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
