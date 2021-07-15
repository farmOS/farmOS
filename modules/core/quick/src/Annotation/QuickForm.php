<?php

namespace Drupal\farm_quick\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a quick form annotation object.
 *
 * @Annotation
 */
class QuickForm extends Plugin {

  /**
   * The quick form ID.
   *
   * @var string
   */
  public $id;

  /**
   * The quick form label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The quick form description.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * The quick form help text.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $helpText;

}
