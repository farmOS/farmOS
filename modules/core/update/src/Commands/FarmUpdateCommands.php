<?php

namespace Drupal\farm_update\Commands;

use Drupal\farm_update\FarmUpdateInterface;
use Drush\Commands\DrushCommands;

/**
 * Farm Update Drush commands.
 *
 * @ingroup farm
 */
class FarmUpdateCommands extends DrushCommands {

  /**
   * Farm update service.
   *
   * @var \Drupal\farm_update\FarmUpdateInterface
   */
  protected $farmUpdate;

  /**
   * FarmUpdateCommands constructor.
   *
   * @param \Drupal\farm_update\FarmUpdateInterface $farm_update
   *   Farm update service.
   */
  public function __construct(FarmUpdateInterface $farm_update) {
    parent::__construct();
    $this->farmUpdate = $farm_update;
  }

  /**
   * Rebuild farmOS configuration.
   *
   * @command farm_update:rebuild
   *
   * @usage farm_update:rebuild
   */
  public function rebuild() {
    $this->farmUpdate->rebuild();
  }

}
