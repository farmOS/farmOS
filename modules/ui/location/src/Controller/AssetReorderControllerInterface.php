<?php

namespace Drupal\farm_ui_location\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Interface for the drag and drop controller.
 */
interface AssetReorderControllerInterface {

  /**
   * Builds the response.
   */
  public function access(AccountInterface $account, AssetInterface $asset = NULL);

  /**
   * Builds the response.
   */
  public function build(AssetInterface $asset = NULL);

}
