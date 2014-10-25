<?php

/**
 * @file
 * Farm asset classes.
 */

/**
 * Farm asset class.
 */
class FarmAsset extends Entity {
  protected function defaultLabel() {
    return $this->name;
  }

  protected function defaultUri() {
    return array('path' => 'farm/asset/' . $this->identifier());
  }
}

/**
 * Farm asset controller class.
 */
class FarmAssetController extends EntityAPIController {

  // Initialize an empty object.
  public function create(array $values = array()) {
    global $user;
    $values += array(
      'id' => NULL,
      'name' => '',
      'type' => '',
      'uid' => $user->uid,
      'created' => REQUEST_TIME,
      'changed' => REQUEST_TIME,
    );
    return parent::create($values);
  }

  // Set the changed timestamp when an asset entity is saved.
  public function save($entity, DatabaseTransaction $transaction = NULL) {
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

  public function __construct($values = array()) {
    parent::__construct($values, 'farm_asset_type');
  }

  function isLocked() {
    return isset($this->status) && empty($this->is_new) && (($this->status & ENTITY_IN_CODE) || ($this->status & ENTITY_FIXED));
  }
}

/**
 * Farm asset type controller class.
 */
class FarmAssetTypeController extends EntityAPIControllerExportable {
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