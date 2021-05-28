<?php

namespace Drupal\farm_ui_location\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
