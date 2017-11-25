<?php

/**
 * @file
 * Farm plan classes.
 */

/**
 * Farm plan class.
 */
class FarmPlan extends Entity {

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
    return array('path' => 'farm/plan/' . $this->identifier());
  }

}

/**
 * Farm plan controller class.
 */
class FarmPlanController extends EntityAPIController {

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
 * Farm plan type class.
 */
class FarmPlanType extends Entity {
  public $type;
  public $label;
  public $weight = 0;

  /**
   * {@inheritdoc}
   */
  public function __construct($values = array()) {
    parent::__construct($values, 'farm_plan_type');
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    return isset($this->status) && empty($this->is_new) && (($this->status & ENTITY_IN_CODE) || ($this->status & ENTITY_FIXED));
  }

}

/**
 * Farm plan type controller class.
 */
class FarmPlanTypeController extends EntityAPIControllerExportable {

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
 * UI controller for farm plan types.
 */
class FarmPlanTypeUIController extends EntityDefaultUIController {

  /**
   * Overrides hook_menu() defaults.
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    $items[$this->path]['description'] = 'Manage farm plan types.';
    return $items;
  }

}
