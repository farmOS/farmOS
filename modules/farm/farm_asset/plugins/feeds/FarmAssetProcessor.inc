<?php
/**
 * @file
 * Class definition of FarmAssetProcessor.
 */

/**
 * Creates farm assets from feed items.
 */
class FarmAssetProcessor extends FeedsProcessor {

  /**
   * Define entity type.
   */
  public function entityType() {
    return 'farm_asset';
  }

  /**
   * Implements parent::entityInfo().
   */
  protected function entityInfo() {
    $info = parent::entityInfo();
    $info['label plural'] = t('Farm assets');
    return $info;
  }

  /**
   * Creates a new farm asset in memory and returns it.
   */
  protected function newEntity(FeedsSource $source) {
    $farm_asset = parent::newEntity($source);
    $farm_asset->type = $this->bundle();
    $farm_asset->changed = REQUEST_TIME;
    $farm_asset->created = REQUEST_TIME;
    $farm_asset->is_new = TRUE;
    $farm_asset->uid = $this->config['author'];
    return $farm_asset;
  }

  /**
   * Loads an existing farm asset.
   *
   * If the update existing method is not FEEDS_UPDATE_EXISTING, only the
   * farm_asset table will be loaded, foregoing the farm_asset_load API for
   * better performance.
   */
  protected function entityLoad(FeedsSource $source, $id) {
    $farm_asset = parent::entityLoad($source, $id);

    if ($this->config['update_existing'] != FEEDS_UPDATE_EXISTING) {
      $farm_asset->uid = $this->config['author'];
    }

    return $farm_asset;
  }

  /**
   * Check that the user has permission to save a farm asset.
   */
  protected function entitySaveAccess($entity) {

    // The check will be skipped for anonymous assets.
    if ($this->config['authorize'] && !empty($entity->uid)) {

      $author = user_load($entity->uid);

      // If the uid was mapped directly, rather than by email or username, it
      // could be invalid.
      if (!$author) {
        $message = 'User %uid is not a valid user.';
        throw new FeedsAccessException(t($message, array('%uid' => $entity->uid)));
      }

      if (empty($entity->id) || !empty($entity->is_new)) {
        $op = 'create';
        $access = farm_asset_access($op, $entity->type, $author);
      }
      else {
        $op = 'update';
        $access = farm_asset_access($op, $entity, $author);
      }

      if (!$access) {
        $message = t('The user %name is not authorized to %op assets of type %content_type. To import this item, either the user "@name" (author of the item) must be given the permission to @op assets of type @content_type, or the option "Authorize" on the farm asset processor settings must be turned off.', array(
          '%name' => $author->name,
          '%op' => $op,
          '%content_type' => $entity->type,
          '@name' => $author->name,
          '@op' => $op,
          '@content_type' => $entity->type,
        ));
        throw new FeedsAccessException($message);
      }
    }
  }

  /**
   * Validates a farm asset.
   */
  protected function entityValidate($entity) {
    parent::entityValidate($entity);

    if (!isset($entity->uid) || !is_numeric($entity->uid)) {
      $entity->uid = $this->config['author'];
    }
  }

  /**
   * Save a farm asset.
   */
  public function entitySave($entity) {
    farm_asset_save($entity);
  }

  /**
   * Delete a series of farm assets.
   */
  protected function entityDeleteMultiple($ids) {
    farm_asset_delete_multiple($ids);
  }

  /**
   * Override parent::configDefaults().
   */
  public function configDefaults() {
    return array(
        'author' => 0,
        'authorize' => TRUE,
      ) + parent::configDefaults();
  }

  /**
   * Override parent::configForm().
   */
  public function configForm(&$form_state) {
    $form = parent::configForm($form_state);

    $author = user_load($this->config['author']);
    $form['author'] = array(
      '#type' => 'textfield',
      '#title' => t('Author'),
      '#description' => t('Select the author of the assets to be created - leave empty to assign "anonymous".'),
      '#autocomplete_path' => 'user/autocomplete',
      '#default_value' => empty($author->name) ?  'anonymous' : check_plain($author->name),
    );
    $form['authorize'] = array(
      '#type' => 'checkbox',
      '#title' => t('Authorize'),
      '#description' => t('Check that the author has permission to create the asset.'),
      '#default_value' => $this->config['authorize'],
    );
    return $form;
  }

  /**
   * Override parent::configFormValidate().
   */
  public function configFormValidate(&$values) {
    if ($author = user_load_by_name($values['author'])) {
      $values['author'] = $author->uid;
    }
    else {
      $values['author'] = 0;
    }
  }

  /**
   * Override setTargetElement to operate on a target item that is a farm asset.
   */
  public function setTargetElement(FeedsSource $source, $target_asset, $target_element, $value) {
    switch ($target_element) {
      case 'created':
        $target_asset->created = feeds_to_unixtime($value, REQUEST_TIME);
        break;
      case 'changed':
        // The 'changed' value will be set on the asset in
        // farm_asset_entity_presave(). This is because farm_asset_save()
        // always overwrites this value (though before invoking
        // hook_entity_presave()).
        $target_asset->feeds_item->asset_changed = feeds_to_unixtime($value, REQUEST_TIME);
        break;
      case 'user_name':
        if ($user = user_load_by_name($value)) {
          $target_asset->uid = $user->uid;
        }
        break;
      case 'user_mail':
        if ($user = user_load_by_mail($value)) {
          $target_asset->uid = $user->uid;
        }
        break;
      default:
        parent::setTargetElement($source, $target_asset, $target_element, $value);
        break;
    }
  }

  /**
   * Return available mapping targets.
   */
  public function getMappingTargets() {
    $targets = parent::getMappingTargets();
    $targets['id'] = array(
      'name' => t('Asset ID'),
      'description' => t('The id of the asset. NOTE: use this feature with care, asset ids are usually assigned by Drupal.'),
      'optional_unique' => TRUE,
    );
    $targets['name'] = array(
      'name' => t('Name'),
      'description' => t('The name of the asset.'),
    );
    $targets['uid'] = array(
      'name' => t('Author user ID'),
      'description' => t('The user ID of the asset author.'),
    );
    $targets['user_name'] = array(
      'name' => t('Author username'),
      'description' => t('The username of the asset author.'),
    );
    $targets['user_mail'] = array(
      'name' => t('Author user email'),
      'description' => t('The email address of the asset author.'),
    );
    $targets['created'] = array(
      'name' => t('Created date'),
      'description' => t('The UNIX time when an asset has been published.'),
    );
    $targets['changed'] = array(
      'name' => t('Updated date'),
      'description' => t('The Unix timestamp when an asset has been last updated.'),
    );
    $targets['active'] = array(
      'name' => t('Active'),
      'description' => t('Whether an asset is active or not. 1 stands for active, 0 for not active.'),
    );

    $this->getHookTargets($targets);

    return $targets;
  }

  /**
   * Get id of an existing feed item asset if available.
   */
  protected function existingEntityId(FeedsSource $source, FeedsParserResult $result) {
    if ($id = parent::existingEntityId($source, $result)) {
      return $id;
    }

    // Iterate through all unique targets and test whether they do already
    // exist in the database.
    foreach ($this->uniqueTargets($source, $result) as $target => $value) {
      switch ($target) {
        case 'id':
          $id = db_query("SELECT id FROM {farm_asset} WHERE id = :id", array(':id' => $value))->fetchField();
          break;
      }
      if (!empty($id)) {
        // Return with the first id found.
        return $id;
      }
    }
    return 0;
  }

}
