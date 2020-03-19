<?php
/**
 * @file
 * farmOS timezone setup form.
 */

/**
 * Form callback for the timezone setup form.
 */
function farm_setup_timezone_form($form, &$form_state) {
  $form = array();

  // Load system time zones.
  $zones = system_time_zones();

  $form['timezone'] = array(
    '#type' => 'fieldset',
    '#title' => t('Default Timezone'),
    '#collapsible' => FALSE,
  );

  $form['timezone']['date_default_timezone'] = array(
    '#type' => 'select',
    '#title' => t('Timezone'),
    '#options' => $zones,
    '#default_value' => variable_get('date_default_timezone', date_default_timezone_get()),
    '#description' => t('The default timezone for the farmOS server. Note that users can configure individual timezones later.'),
  );

  $form['timezone']['change_existing_users'] = array(
    '#type' => 'checkbox',
    '#title' => t('Update the timezone for existing farmOS users.'),
    '#default_value' => TRUE,
    '#description' => t('If you are configuring farmOS for the first time, select this to update the timezone for your user.'),
  );

  return $form;
}


/**
 * Submit handler for timezone setup form.
 */
function farm_setup_timezone_form_submit($form, &$form_state) {

  // Update the default timezone.
  $default_timezone = $form_state['values']['date_default_timezone'];
  variable_set('date_default_timezone', $default_timezone);
  drupal_set_message('Default timezone saved.');

  // Change existing users timezone if checked.
  $change_users = $form_state['values']['change_existing_users'];
  if ($change_users) {

    // Load users.
    $users = entity_load('user');
    foreach($users as $user) {
      // Properties required for the user_save function.
      $user->is_new = 0;
      $user->original = entity_load_unchanged('user', $user->uid);

      // Update the user timezone.
      user_save($user, array('timezone'=>$default_timezone));
    }

    drupal_set_message('Updated user timezones');
  }

}
