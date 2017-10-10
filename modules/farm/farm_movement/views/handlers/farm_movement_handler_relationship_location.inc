<?php

/**
 * @file
 * Farm log location relationship handler.
 */

/**
 * Farm log movement location relationship handler.
 *
 * Create a relationship between a farm_asset, and it's most recent log entity's
 * movement field collection, in order to determine its current location.
 *
 * The following Views handlers were used as examples to develop this:
 * views_handler_relationship_entity_reverse
 * views_handler_relationship_node_term_data
 */
class farm_movement_handler_relationship_location extends views_handler_relationship {

  /**
   * {@inheritfoc}
   */
  public function option_definition() {
    $options = parent::option_definition();

    // Only include "done" logs by default.
    $options['done'] = array('default' => TRUE, 'bool' => TRUE);

    // Don't include future logs by default.
    $options['future'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * {@inheritfoc}
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    // Only include log that are "done".
    $form['done'] = array(
      '#type' => 'checkbox',
      '#title' => t('Only include done logs'),
      '#description' => t('Filters out logs that have not been marked as done.'),
      '#default_value' => !empty($this->options['done']),
    );

    // Allow future logs to be included.
    $form['future'] = array(
      '#type' => 'checkbox',
      '#title' => t('Include future logs'),
      '#description' => t('Finds the last log, even if it is in the future.'),
      '#default_value' => !empty($this->options['future']),
    );
  }

  /**
   * {@inheritfoc}
   */
  public function query() {

    // Figure out what base table this relationship brings to the party.
    $table_data = views_fetch_data($this->definition['base']);
    $base_field = empty($this->definition['base field']) ? $table_data['table']['base']['field'] : $this->definition['base field'];

    $this->ensure_my_table();

    // Build a sub-query that will be used in the join to load the latest
    // movement log of a given asset.
    $asset_id_field = $this->table_alias . '.id';
    $done = !empty($this->options['done']) ? TRUE : FALSE;
    $future = !empty($this->options['future']) ? TRUE : FALSE;
    if ($future) {
      $time = 0;
    }
    else {
      $time = REQUEST_TIME;
    }
    $query = farm_movement_asset_movement_query($asset_id_field, $time, $done, 'movement_id');

    // Build the join definition.
    $def = $this->definition;
    $def['table'] = $this->definition['base'];
    $def['field'] = $base_field;
    $def['left_table'] = $this->table_alias;
    $def['left_field'] = $this->real_field;
    $def['left_query'] = $query;
    if (!empty($this->options['required'])) {
      $def['type'] = 'INNER';
    }

    if (!empty($this->definition['extra'])) {
      $def['extra'] = $this->definition['extra'];
    }

    // Create the join as an instance of the views_join_subquery class.
    $join = new views_join_subquery();

    $join->definition = $def;
    $join->options = $this->options;
    $join->construct();
    $join->adjusted = TRUE;

    // Use a short alias for this.
    $alias = $def['table'] . '_' . $this->table;

    $this->alias = $this->query->add_relationship($alias, $join, $this->definition['base'], $this->relationship);

    // Add access tags if the base table provide it.
    if (empty($this->query->options['disable_sql_rewrite']) && isset($table_data['table']['base']['access query tag'])) {
      $access_tag = $table_data['table']['base']['access query tag'];
      $this->query->add_tag($access_tag);
    }
  }

}
