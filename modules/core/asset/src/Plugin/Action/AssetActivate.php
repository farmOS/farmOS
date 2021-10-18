<?php

namespace Drupal\asset\Plugin\Action;

/**
 * Action that makes an asset active.
 *
 * @Action(
 *   id = "asset_activate_action",
 *   label = @Translation("Makes an Asset active"),
 *   type = "asset"
 * )
 */
class AssetActivate extends AssetStateChangeBase {

  /**
   * {@inheritdoc}
   */
  protected $targetState = 'active';

}
