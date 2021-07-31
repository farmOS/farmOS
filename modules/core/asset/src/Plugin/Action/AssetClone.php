<?php

namespace Drupal\asset\Plugin\Action;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Action that clones an asset.
 *
 * @Action(
 *   id = "asset_clone_action",
 *   label = @Translation("Clone an asset"),
 *   type = "asset"
 * )
 */
class AssetClone extends EntityActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute(AssetInterface $asset = NULL) {
    if ($asset) {
      $cloned_asset = $asset->createDuplicate();
      $new_name = $asset->getName() . ' ' . $this->t('(clone of asset #@id)', ['@id' => $asset->id()]);
      $cloned_asset->setName($new_name);
      $cloned_asset->save();
      $this->messenger()->addMessage($this->t('Asset saved: <a href=":uri">%asset_label</a>', [':uri' => $cloned_asset->toUrl()->toString(), '%asset_label' => $cloned_asset->label()]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\asset\Entity\AssetInterface $object */
    $result = $object->access('view', $account, TRUE)
      ->andIf($object->access('create', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
