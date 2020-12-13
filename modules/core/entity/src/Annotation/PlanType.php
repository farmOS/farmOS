<?php

namespace Drupal\farm_entity\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the plan type plugin annotation object.
 *
 * Plugin namespace: Plugin\Plan\PlanType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class PlanType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plan type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
