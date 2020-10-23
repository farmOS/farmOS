<?php

namespace Drupal\data_stream\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a DataStream annotation object.
 *
 * @Annotation
 */
class DataStream extends Plugin {

  /**
   * The DataStream plugin id.
   *
   * @var string
   */
  public $id;

  /**
   * The DataStream plugin label.
   *
   * @var string
   */
  public $label;

}
