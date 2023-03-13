<?php

namespace Drupal\farm_quick\Traits;

use Drupal\Component\Utility\NestedArray;

/**
 * Implements \Drupal\Component\Plugin\ConfigurableInterface.
 *
 * In order for configurable plugins to maintain their configuration, the
 * default configuration must be merged into any explicitly defined
 * configuration. This trait provides the appropriate getters and setters to
 * handle this logic, removing the need for excess boilerplate.
 *
 * @ingroup Plugin
 *
 * @todo Replace with core trait when available.
 * @see https://www.drupal.org/project/drupal/issues/2852463
 */
trait ConfigurableTrait {

  /**
   * Configuration information passed into the plugin.
   *
   * @var array
   */
  protected $configuration;

  /**
   * Gets this plugin's configuration.
   *
   * @return array
   *   An array of this plugin's configuration.
   *
   * @see \Drupal\Component\Plugin\ConfigurableInterface::getConfiguration()
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * Sets the configuration for this plugin instance.
   *
   * @param array $configuration
   *   An associative array containing the plugin's configuration. Provided
   *   value is merged with default configuration.
   *
   * @return $this
   *
   * @see \Drupal\Component\Plugin\ConfigurableInterface::setConfiguration()
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeepArray([$this->defaultConfiguration(), $configuration], TRUE);
    return $this;
  }

  /**
   * Gets default configuration for this plugin.
   *
   * @return array
   *   An associative array with the default configuration.
   *
   * @see \Drupal\Component\Plugin\ConfigurableInterface::defaultConfiguration()
   */
  public function defaultConfiguration() {
    return [];
  }

}
