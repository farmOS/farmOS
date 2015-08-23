<?php

/**
 * @file
 * Farm asset classes.
 */

/**
 * Farm asset class.
 */
class FarmAsset extends Entity {

  /**
   * {@inheritdoc}
   */
  protected function defaultLabel() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  protected function defaultUri() {
    return array('path' => 'farm/asset/' . $this->identifier());
  }

}

/**
 * Farm asset controller class.
 */
class FarmAssetController extends EntityAPIController {

  /**
   * {@inheritdoc}
   */
  public function create(array $values = array()) {
    global $user;
    $values += array(
      'id' => NULL,
      'name' => '',
      'type' => '',
      'uid' => $user->uid,
      'created' => REQUEST_TIME,
      'changed' => REQUEST_TIME,
      'active' => TRUE,
    );
    return parent::create($values);
  }

  /**
   * {@inheritdoc}
   */
  public function save($entity, DatabaseTransaction $transaction = NULL) {

    // Set the changed timestamp.
    $entity->changed = REQUEST_TIME;
    return parent::save($entity, $transaction);
  }

}

/**
 * Farm asset type class.
 */
class FarmAssetType extends Entity {
  public $type;
  public $label;
  public $weight = 0;

  /**
   * {@inheritdoc}
   */
  public function __construct($values = array()) {
    parent::__construct($values, 'farm_asset_type');
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    return isset($this->status) && empty($this->is_new) && (($this->status & ENTITY_IN_CODE) || ($this->status & ENTITY_FIXED));
  }

}

/**
 * Farm asset type controller class.
 */
class FarmAssetTypeController extends EntityAPIControllerExportable {

  /**
   * {@inheritdoc}
   */
  public function create(array $values = array()) {
    $values += array(
      'id' => NULL,
      'type' => '',
      'label' => '',
    );
    return parent::create($values);
  }

}

/**
 * UI controller for farm asset types.
 */
class FarmAssetTypeUIController extends EntityDefaultUIController {

  /**
   * Overrides hook_menu() defaults.
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    $items[$this->path]['description'] = 'Manage farm asset types.';
    return $items;
  }

}
