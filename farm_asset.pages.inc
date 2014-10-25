<?php

/**
 * @file
 * Farm asset pages
 */

/**
 * Asset view callback.
 */
function farm_asset_view($farm_asset) {
  drupal_set_title(entity_label('farm_asset', $farm_asset));
  return entity_view('farm_asset', array(entity_id('farm_asset', $farm_asset) => $farm_asset), 'full');
}

/**
 * Page to select asset type to add new asset.
 */
function farm_asset_add_types_page() {
  $items = array();
  foreach (farm_asset_types() as $farm_asset_type_key => $farm_asset_type) {
    if (farm_asset_access('create', $farm_asset_type)) {
      $items[] = l(entity_label('farm_asset_type', $farm_asset_type), 'farm/asset/add/' . $farm_asset_type_key);
    }
  }
  return array(
    'list' => array(
      '#theme' => 'item_list',
      '#items' => $items,
      '#title' => t('Select a type of asset to create.'),
    ),
  );
}

/**
 * Add new asset page callback.
 */
function farm_asset_add($type) {
  $farm_asset_type = farm_asset_types($type);

  $farm_asset = entity_create('farm_asset', array('type' => $type));
  drupal_set_title(t('Add @name', array('@name' => entity_label('farm_asset_type', $farm_asset_type))));

  $output = drupal_get_form('farm_asset_form', $farm_asset);

  return $output;
}

/**
 * Asset form.
 */
function farm_asset_form($form, &$form_state, $farm_asset) {

  $form['farm_asset'] = array(
    '#type' => 'value',
    '#value' => $farm_asset,
  );

  // Load the asset type.
  $farm_asset_type = farm_asset_type_load($farm_asset->type);

  // Asset name
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#description' => t('Give this %type a name.', array('%type' => $farm_asset_type->label)),
    '#default_value' => $farm_asset->name,
    '#weight' => -100,
  );

  $form['uid'] = array(
    '#type' => 'value',
    '#value' => $farm_asset->uid,
  );

  field_attach_form('farm_asset', $farm_asset, $form, $form_state);

  $submit = array();
  if (!empty($form['#submit'])) {
    $submit += $form['#submit'];
  }

  $form['actions'] = array(
    '#weight' => 100,
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#submit' => $submit + array('farm_asset_form_submit'),
  );

  // Show Delete button if allowed.
  $farm_asset_id = entity_id('farm_asset' ,$farm_asset);
  if (!empty($farm_asset_id) && farm_asset_access('delete', $farm_asset)) {

    // Get the destination query parameter. If it is the current path, change to <front>
    // (because the current path won't exist once the asset is deleted).
    $destination = drupal_get_destination();
    if ($destination['destination'] == current_path()) {
      $destination['destination'] = '<front>';
    }
    $form['actions']['delete'] = array(
      '#type' => 'markup',
      '#markup' => l(t('Delete'), 'farm/asset/' . $farm_asset_id . '/delete', array('query' => $destination)),
    );
  }

  return $form;
}

/**
 * Asset validate handler.
 */
function farm_asset_form_validate($form, &$form_state) {

}

/**
 * Asset submit handler.
 */
function farm_asset_form_submit($form, &$form_state) {
  $farm_asset = $form_state['values']['farm_asset'];

  entity_form_submit_build_entity('farm_asset', $farm_asset, $form, $form_state);

  farm_asset_save($farm_asset);

  $farm_asset_uri = entity_uri('farm_asset', $farm_asset);

  $form_state['redirect'] = $farm_asset_uri['path'];

  drupal_set_message(t('%title saved.', array('%title' => entity_label('farm_asset', $farm_asset))));
}

/**
 * Delete confirmation form.
 */
function farm_asset_delete_form($form, &$form_state, $farm_asset) {
  $form['farm_asset'] = array(
    '#type' => 'value',
    '#value' => $farm_asset,
  );
  // Always provide entity id in the same form key as in the entity edit form.
  $form['farm_asset_type_id'] = array('#type' => 'value', '#value' => entity_id('farm_asset' ,$farm_asset));
  $farm_asset_uri = entity_uri('farm_asset', $farm_asset);
  return confirm_form($form,
    t('Are you sure you want to delete %title?', array('%title' => entity_label('farm_asset', $farm_asset))),
    $farm_asset_uri['path'],
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Delete form submit handler.
 */
function farm_asset_delete_form_submit($form, &$form_state) {
  $farm_asset = $form_state['values']['farm_asset'];
  farm_asset_delete($farm_asset);

  drupal_set_message(t('%title was deleted.', array('%title' => entity_label('farm_asset', $farm_asset))));

  $form_state['redirect'] = '<front>';
}