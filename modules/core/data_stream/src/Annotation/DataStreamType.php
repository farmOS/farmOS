<?php

namespace Drupal\data_stream\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the data stream type plugin annotation object.
 *
 * Plugin namespace: Plugin\DataStream\DataStreamType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class DataStreamType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The data stream type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
