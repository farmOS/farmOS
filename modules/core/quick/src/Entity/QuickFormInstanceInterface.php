<?php

namespace Drupal\farm_quick\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining quick form instance config entities.
 */
interface QuickFormInstanceInterface extends ConfigEntityInterface {

  /**
   * Returns the plugin instance.
   *
   * @return \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface
   *   The plugin instance for this quick form.
   */
  public function getPlugin();

  /**
   * Returns the plugin ID.
   *
   * @return string
   *   The plugin ID for this quick form.
   */
  public function getPluginId();

  /**
   * Returns the quick form label.
   *
   * @return string
   *   The label for this quick form.
   */
  public function getLabel();

  /**
   * Returns the quick form description.
   *
   * @return string
   *   The description for this quick form.
   */
  public function getDescription();

  /**
   * Returns the quick form help text.
   *
   * @return string
   *   The help text for this quick form.
   */
  public function getHelpText();

  /**
   * Returns the quick form settings.
   *
   * @return array
   *   An associative array of settings.
   */
  public function getSettings();

}
