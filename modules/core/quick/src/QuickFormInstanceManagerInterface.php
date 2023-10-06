<?php

namespace Drupal\farm_quick;

/**
 * Quick form instance manager.
 */
interface QuickFormInstanceManagerInterface {

  /**
   * Get all quick form instances.
   *
   * @return \Drupal\farm_quick\Entity\QuickFormInstanceInterface[]
   *   An array of quick form instances.
   */
  public function getInstances();

  /**
   * Get an instance of a quick form.
   *
   * @param string $id
   *   The quick form ID.
   *
   * @return \Drupal\farm_quick\Entity\QuickFormInstanceInterface|null
   *   Returns an instantiated quick form object.
   */
  public function getInstance($id);

}
