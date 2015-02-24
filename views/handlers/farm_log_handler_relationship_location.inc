<?php

/**
 * @file
 * Farm log location relationship handler.
 */

/**
 * Create a relationship between a farm_asset, and it's most recent movement
 * log entity, in order to determine its current location.
 *
 * The following Views handlers were used as examples to develop this:
 * views_handler_relationship_entity_reverse
 * views_handler_relationship_node_term_data
 */
class farm_log_handler_relationship_location extends views_handler_relationship_entity_reverse {

  /**
   * Default options.
   */
  function option_definition() {
    $options = parent::option_definition();

    // Don't include future logs by default.
    $options['future'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * Add an option for including future logs.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    // Allow future logs to be included.
    $form['future'] = array(
      '#type' => 'checkbox',
      '#title' => t('Include future logs'),
      '#description' => t('Finds the latest log, even if it is in the future.'),
      '#default_value' => !empty($this->options['future']),
    );
  }

  /**
   * Called to implement a relationship in a query.
   */
  function query() {

    // Start with the joins of the parent method, which will create a reverse entity reference
    // relationship to all log items that reference the farm asset.
    parent::query();

    // Here's where the special sauce is: we add an additional join extra with
    // a subselect that returns the maximum date value.
    $query = db_select('log', 'ss_log');
    $query->join('field_data_field_farm_asset', 'ss_fdffa', 'ss_log.id = ss_fdffa.entity_id');
    /**
     * @todo
     * Change where()s to condition()s.
     * See related @todo below for more details.
     */
    $query->where("ss_log.type = 'farm_movement'");
    $query->where('ss_fdffa.field_farm_asset_target_id = ' . $this->table_alias . '.id');
    $query->orderBy('ss_log.timestamp', 'DESC');
    $query->orderBy('ss_log.id', 'DESC');
    $query->range(0, 1);
    $query->addField('ss_log', 'id');

    // If future logs should not be included, then limit to only logs that
    // have a date before right now.
    if (empty($this->options['future'])) {
      $query->where('ss_log.timestamp <= ' . REQUEST_TIME);
    }

    // Add the subselect query as a join extra.
    /**
     * @todo
     * Casting the query as a string here does not take the placeholder naming
     * of the parent query into account, so methods that use placeholders
     * (like condition()) end up getting replaced with the wrong values.
     * Figure out how to do this the right way, and then change the where()s to
     * condition()s in the above subquery.
     */
    $join_extra = $this->alias . '.id = (' . $query . ')';

    // Add the join extra to the second relationship.
    $this->query->table_queue[$this->alias]['join']->extra = $join_extra;

    // Add a condition to the query to filter out any empty dates (this will
    // filter out any logs that don't have the latest date).
    $or = db_or();
    $or->condition($this->alias . '.timestamp', NULL, 'IS NOT NULL');
    // But, be careful not to filter out assets that don't have a location!
    // This OR condition allows rows to be included that don't have a movement.
    $or->condition($this->first_alias . '.entity_id', NULL, 'IS NULL');
    $this->query->add_where(0, $or);
  }
}