<?php

namespace Drupal\farm_entity\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the log type plugin annotation object.
 *
 * Plugin namespace: Plugin\Log\LogType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class LogType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The log type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
