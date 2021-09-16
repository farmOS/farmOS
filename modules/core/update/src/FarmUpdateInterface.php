<?php

namespace Drupal\farm_update;

/**
 * Farm update service interface.
 */
interface FarmUpdateInterface {

  /**
   * Rebuild farmOS configuration.
   */
  public function rebuild(): void;

}
