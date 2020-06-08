<?php

/**
 * @file
 * farmOS installation profile.
 */

/**
 * Implements hook_form_BASE_FORM_ID_alter().
 */
function farm_form_update_manager_update_form_alter(&$form, &$form_state, $form_id) {

  // Disable updating through the UI.
  // @see https://www.drupal.org/project/farm/issues/3136140
  drupal_set_message(t('Performing updates through this interface is disabled by farmOS. For information about updating farmOS, see <a href="!url">!url</a>.', array('!url' => 'https://farmOS.org/hosting/updating')), 'error');
  $form['actions']['#access'] = FALSE;
}
