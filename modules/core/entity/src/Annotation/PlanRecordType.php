<?php

namespace Drupal\farm_entity\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines the plan record relationship type plugin annotation object.
 *
 * Plugin namespace: Plugin\PlanRecord\PlanRecordType.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class PlanRecordType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plan record relationship type label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
