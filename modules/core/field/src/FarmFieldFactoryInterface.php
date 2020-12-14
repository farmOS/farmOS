<?php

namespace Drupal\farm_field;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\BundleFieldDefinition;

/**
 * Provides an interface for defining a farmOS field factory.
 */
interface FarmFieldFactoryInterface {

  /**
   * Generate a base field definition.
   *
   * @param array $options
   *   An array of options.
   *
   * @return \Drupal\Core\Field\BaseFieldDefinition
   *   Returns a base field definition.
   */
  public function baseFieldDefinition(array $options = []): BaseFieldDefinition;

  /**
   * Generates a bundle field definition.
   *
   * @param array $options
   *   An array of options.
   *
   * @return \Drupal\entity\BundleFieldDefinition
   *   Returns a bundle field definition.
   */
  public function bundleFieldDefinition(array $options = []): BundleFieldDefinition;

}
