<?php

namespace Drupal\asset\Plugin\Action;

use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\asset\Entity\AssetInterface;

/**
 * Action that makes an asset active.
 *
 * @Action(
 *   id = "asset_activate_action",
 *   label = @Translation("Makes an Asset active"),
 *   type = "asset"
 * )
 */
class AssetActivate extends EntityActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute(AssetInterface $asset = NULL) {
    if ($asset) {
      $asset->get('status')->first()->applyTransitionById('to_active');
      $asset->setNewRevision(TRUE);
      $asset->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\asset\Entity\AssetInterface $object */
    $result = $object->get('status')->access('edit', $account, TRUE)
      ->andIf($object->access('update', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
