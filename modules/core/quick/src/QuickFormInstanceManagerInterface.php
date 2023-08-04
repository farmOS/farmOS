<?php

namespace Drupal\farm_quick;

/**
 * Quick form instance manager.
 */
interface QuickFormInstanceManagerInterface {

  /**
   * Get all quick form instances.
   *
   * @return array
   *   An array of quick form instances.
   */
  public function getInstances(): array;

  /**
   * Create an instance of a quick form.
   *
   * @param string $id
   *   The quick form ID.
   *
   * @return \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface|null
   *   Returns an instantiated quick form object.
   */
  public function createInstance($id);

}
