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

  /**
   * An array of access permissions for the quick form.
   *
   * @var string[]
   */
  public $permissions;

  /**
   * Require a quick form instance entity to instantiate.
   *
   * @var bool
   */
  public $requiresEntity;

}
