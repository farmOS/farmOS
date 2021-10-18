<?php

namespace Drupal\asset\Plugin\Action;

/**
 * Action that archives an asset.
 *
 * @Action(
 *   id = "asset_archive_action",
 *   label = @Translation("Archive an asset"),
 *   type = "asset"
 * )
 */
class AssetArchive extends AssetStateChangeBase {

  /**
   * {@inheritdoc}
   */
  protected $targetState = 'archived';

}
