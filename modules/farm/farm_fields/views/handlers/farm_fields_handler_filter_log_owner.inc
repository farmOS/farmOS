<?php

/**
 * @file
 * Definition of farm_fields_handler_filter_log_owner.
 */

/**
 * Provide an exposed filter that presents user options in a select list.
 *
 * This is based heavily on the code in views_handler_filter_term_node_tid,
 * modified to simplify and remove unneeded options.
 *
 * @ingroup views_filter_handlers
 */
class farm_fields_handler_filter_log_owner extends views_handler_filter_many_to_one {

  // Stores the exposed input for this filter.
  var $validated_exposed_input = NULL;

  function value_form(&$form, &$form_state) {
    $options = array();

    // Select all active users.
    $query = db_select('users', 'u');
    $query->fields('u');
    $query->condition('u.status', 1);
    $query->orderby('u.name');

    // Add a custom tag to the query so other modules can modify.
    $query->addTag('farm_log_owner');

    // Execute the query.
    $result = $query->execute();

    $uids = array();
    foreach ($result as $user) {
      $uids[] = $user->uid;
    }
    $entities = user_load_multiple($uids);
    foreach ($entities as $entity_user) {
      $options[$entity_user->uid] = entity_label('user', $entity_user);
    }

    $default_value = (array) $this->value;

    if (!empty($form_state['exposed'])) {
      $identifier = $this->options['expose']['identifier'];

      if (!empty($this->options['expose']['reduce'])) {
        $options = $this->reduce_value_options($options);

        if (!empty($this->options['expose']['multiple']) && empty($this->options['expose']['required'])) {
          $default_value = array();
        }
      }

      if (empty($this->options['expose']['multiple'])) {
        if (empty($this->options['expose']['required']) && (empty($default_value) || !empty($this->options['expose']['reduce']))) {
          $default_value = 'All';
        }
        elseif (empty($default_value)) {
          $keys = array_keys($options);
          $default_value = array_shift($keys);
        }
        // Due to #1464174 there is a chance that array('') was saved in the admin ui.
        // Let's choose a safe default value.
        elseif ($default_value == array('')) {
          $default_value = 'All';
        }
        else {
          $copy = $default_value;
          $default_value = array_shift($copy);
        }
      }
    }
    $form['value'] = array(
      '#type' => 'select',
      '#title' => t('Select people'),
      '#multiple' => TRUE,
      '#options' => $options,
      '#size' => min(9, count($options)),
      '#default_value' => $default_value,
    );

    if (!empty($form_state['exposed']) && isset($identifier) && !isset($form_state['input'][$identifier])) {
      $form_state['input'][$identifier] = $default_value;
    }

    if (empty($form_state['exposed'])) {
      // Retain the helper option
      $this->helper->options_form($form, $form_state);

      // Show help text if not exposed to end users.
      $form['value']['#description'] = t('Leave blank for all. Otherwise, the first selected person will be the default instead of "Any".');
    }
  }

  function accept_exposed_input($input) {
    if (empty($this->options['exposed'])) {
      return TRUE;
    }

    // We need to know the operator, which is normally set in
    // views_handler_filter::accept_exposed_input(), before we actually call
    // the parent version of ourselves.
    if (!empty($this->options['expose']['use_operator']) && !empty($this->options['expose']['operator_id']) && isset($input[$this->options['expose']['operator_id']])) {
      $this->operator = $input[$this->options['expose']['operator_id']];
    }

    // If view is an attachment and is inheriting exposed filters, then assume
    // exposed input has already been validated
    if (!empty($this->view->is_attachment) && $this->view->display_handler->uses_exposed()) {
      $this->validated_exposed_input = (array) $this->view->exposed_raw_input[$this->options['expose']['identifier']];
    }

    // If we're checking for EMPTY or NOT, we don't need any input, and we can
    // say that our input conditions are met by just having the right operator.
    if ($this->operator == 'empty' || $this->operator == 'not empty') {
      return TRUE;
    }

    // If it's non-required and there's no value don't bother filtering.
    if (!$this->options['expose']['required'] && empty($this->validated_exposed_input)) {
      return FALSE;
    }

    $rc = parent::accept_exposed_input($input);
    if ($rc) {
      // If we have previously validated input, override.
      if (!$this->is_a_group() && isset($this->validated_exposed_input)) {
        $this->value = $this->validated_exposed_input;
      }
    }

    return $rc;
  }

  function exposed_validate(&$form, &$form_state) {
    if (empty($this->options['exposed'])) {
      return;
    }

    $identifier = $this->options['expose']['identifier'];

    // Get the values from the form state.
    if (isset($form_state['values'][$identifier]) && $form_state['values'][$identifier] != 'All') {
      $this->validated_exposed_input = (array) $form_state['values'][$identifier];
    }
  }

  function admin_summary() {
    // set up $this->value_options for the parent summary
    $this->value_options = array();

    if ($this->value) {
      $this->value = array_filter($this->value);
      $result = user_load_multiple($this->value);
      foreach ($result as $entity_user) {
        $this->value_options[$entity_user->tid] = entity_label('user', $entity_user);
      }
    }
    return parent::admin_summary();
  }
}
