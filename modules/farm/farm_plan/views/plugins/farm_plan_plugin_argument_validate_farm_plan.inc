<?php

/**
 * @file
 * Contains the 'farm plan' argument validator plugin.
 */

/**
 * Validate whether an argument is an acceptable farm_plan entity.
 */
class farm_plan_plugin_argument_validate_farm_plan extends views_plugin_argument_validate {
  function option_definition() {
    $options = parent::option_definition();
    $options['types'] = array('default' => array());
    return $options;
  }

  function options_form(&$form, &$form_state) {
    $types = farm_plan_types();
    $options = array();
    foreach ($types as $type => $definition) {
      $options[$type] = check_plain($definition->label);
    }

    $form['types'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Plan types'),
      '#options' => $options,
      '#default_value' => $this->options['types'],
      '#description' => t('If you wish to validate for specific plan types, check them; if none are checked, all plans will pass.'),
    );
  }

  function options_submit(&$form, &$form_state, &$options = array()) {
    // Filter unselected items so we don't unnecessarily store giant arrays.
    $options['types'] = array_filter($options['types']);
  }

  function validate_argument($argument) {

    // If the argument is not a number, fail.
    if (!is_numeric($argument)) {
      return FALSE;
    }

    // Attempt to load the farm plan entity.
    $farm_plan = farm_plan_load($argument);

    // If the plan didn't load, fail.
    if (empty($farm_plan)) {
      return FALSE;
    }

    // Set the validated title.
    $this->argument->validated_title = check_plain(entity_label('farm_plan', $farm_plan));

    // Load the valid plan types from options, and return TRUE if the list is
    // empty, or if the type is in the list.
    $types = array_filter($this->options['types']);
    return empty($types) || !empty($types[$farm_plan->type]);
  }
}
