<?php

/**
 * @file
 * Definition of farm_asset_handler_relationship_entity_reverse.
 */

/**
 * A relationship handler for reverse entity references.
 *
 * @ingroup views_relationship_handlers
 */
class farm_asset_handler_relationship_entity_reverse extends views_handler_relationship_entity_reverse {

  function option_definition() {
    $options = parent::option_definition();

    // Add an option for limiting the join to non-archived assets.
    $options['archived'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * Default options form that provides the "archived" checkbox.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['archived'] = array(
      '#type' => 'checkbox',
      '#title' => t('Archived assets'),
      '#description' => t('Include archived assets.'),
      '#default_value' => !empty($this->options['archived']),
    );
  }

  /**
   * Called to implement a relationship in a query.
   */
  function query() {

    // Execute the parent method first.
    parent::query();

    // Start the definition of a second join. This is copied directly from
    // views_handler_relationship_entity_reverse.inc.
    $second = array(
      'left_table' => $this->first_alias,
      'left_field' => 'entity_id',
      'table' => $this->definition['base'],
      'field' => $this->definition['base field'],
    );

    // If the "archived" option is not set, add an extra join condition that
    // excludes archived assets from the join.
    if (empty($this->options['archived'])) {
      $second['extra'] = array(
        0 => array(
          'field' => 'archived',
          'operator' => 'IS',
          'value' => 0,
          'numeric' => TRUE,
        ),
      );
    }

    /**
     * Everything below this comment is copied directly from
     * views_handler_relationship_entity_reverse.inc.
     */
    if (!empty($this->options['required'])) {
      $second['type'] = 'INNER';
    }

    if (!empty($this->definition['join_handler']) && class_exists($this->definition['join_handler'])) {
      $second_join = new $this->definition['join_handler'];
    }
    else {
      $second_join = new views_join();
    }
    $second_join->definition = $second;
    $second_join->construct();
    $second_join->adjusted = TRUE;

    // use a short alias for this:
    $alias = $this->definition['field_name'] . '_' . $this->table;

    $this->alias = $this->query->add_relationship($alias, $second_join, $this->definition['base'], $this->relationship);
  }
}
