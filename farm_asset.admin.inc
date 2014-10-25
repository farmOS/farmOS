<?php

/**
 * @file
 * Farm asset admin pages
 */

/**
 * Generates the farm asset type editing form.
 */
function farm_asset_type_form($form, &$form_state, $farm_asset_type, $op = 'edit') {

  if ($op == 'clone') {
    $farm_asset_type->label .= ' (cloned)';
    $farm_asset_type->type = '';
  }

  $form['label'] = array(
    '#title' => t('Label'),
    '#type' => 'textfield',
    '#default_value' => $farm_asset_type->label,
    '#description' => t('The human-readable name of this asset type.'),
    '#required' => TRUE,
    '#size' => 30,
  );

  // Machine-readable type name.
  $form['type'] = array(
    '#type' => 'machine_name',
    '#default_value' => !empty($farm_asset_type->type) ? $farm_asset_type->type : '',
    '#maxlength' => 32,
    '#disabled' => $farm_asset_type->isLocked() && $op != 'clone',
    '#machine_name' => array(
      'exists' => 'farm_asset_types',
      'source' => array('label'),
    ),
    '#description' => t('A unique machine-readable name for this asset type. It must only contain lowercase letters, numbers, and underscores.'),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save asset type'),
    '#weight' => 40,
  );

  if (!$farm_asset_type->isLocked() && $op != 'add' && $op != 'clone') {
    $form['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete asset type'),
      '#weight' => 45,
      '#limit_validation_errors' => array(),
      '#submit' => array('farm_asset_type_form_submit_delete')
    );
  }
  return $form;
}

/**
 * Submit handler for creating/editing asset types.
 */
function farm_asset_type_form_submit(&$form, &$form_state) {
  $farm_asset_type = entity_ui_form_submit_build_entity($form, $form_state);
  // Save and go back.
  farm_asset_type_save($farm_asset_type);

  // Redirect user back to list of asset types.
  $form_state['redirect'] = 'admin/config/farm/asset-types';
}

function farm_asset_type_form_submit_delete(&$form, &$form_state) {
  $form_state['redirect'] = 'admin/config/farm/asset-types/' . $form_state['farm_asset_type']->type . '/delete';
}

/**
 * Asset type delete form.
 */
function farm_asset_type_form_delete_confirm($form, &$form_state, $farm_asset_type) {
  $form['farm_asset_type'] = array(
    '#type' => 'value',
    '#value' => $farm_asset_type,
  );
  // Always provide entity id in the same form key as in the entity edit form.
  $form['farm_asset_type_id'] = array('#type' => 'value', '#value' => entity_id('farm_asset_type' ,$farm_asset_type));
  return confirm_form($form,
    t('Are you sure you want to delete asset type %title?', array('%title' => entity_label('farm_asset_type', $farm_asset_type))),
    'farm/asset/' . entity_id('farm_asset_type' ,$farm_asset_type),
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Asset type delete form submit handler.
 */
function farm_asset_type_form_delete_confirm_submit($form, &$form_state) {
  $farm_asset_type = $form_state['values']['farm_asset_type'];
  farm_asset_type_delete($farm_asset_type);

  watchdog('farm_asset', '@type: deleted %title.', array('@type' => $farm_asset_type->type, '%title' => $farm_asset_type->label));
  drupal_set_message(t('@type %title has been deleted.', array('@type' => $farm_asset_type->type, '%title' => $farm_asset_type->label)));

  $form_state['redirect'] = 'admin/config/farm/asset-types';
}
