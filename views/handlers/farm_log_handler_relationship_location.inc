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
   * Called to implement a relationship in a query.
   */
  function query() {

    // Start with the joins of the parent method, which will create a reverse entity reference
    // relationship to all log items that reference the farm asset.
    parent::query();

    // Relate the log entity to it's date field data.
    $def = array(
      'left_table' => $this->alias,
      'left_field' => 'id',
      'table' => $this->definition['date table'],
      'field' => $this->definition['date field'],
    );

    $def['type'] = empty($this->options['required']) ? 'LEFT' : 'INNER';

    if (!empty($this->definition['join_extra'])) {
      $def['extra'] = $this->definition['join_extra'];
    }

    if (!empty($this->definition['join_handler']) && class_exists($this->definition['join_handler'])) {
      $join = new $this->definition['join_handler'];
    }
    else {
      $join = new views_join();
    }
    $join->definition = $def;
    $join->construct();
    $join->adjusted = TRUE;

    // use a short alias for this:
    $alias = $this->alias . '_' . $this->definition['date field'];

    // Build the join extras into individual string statements.
    // Normally this is done in views_join::build_join() but we need to
    // simulate it here so that we can add our custom subselect join extra below.
    $join_extras = array();
    foreach ($join->extra as $extra) {
      $value = !empty($extra['numeric']) ? $extra['value'] : "'" . $extra['value'] . "'";
      $join_extras[] = $alias . '.' . $extra['field'] . ' = ' . $value;
    }

    // Here's where the special sauce is: we add an additional join extra with
    // a subselect that returns the maximum date value.
    $query = db_select('log', 'ss_log');
    $query->join('field_data_field_farm_date', 'ss_fdffd', 'ss_log.id = ss_fdffd.entity_id');
    $query->join('field_data_field_farm_asset', 'ss_fdffa', 'ss_log.id = ss_fdffa.entity_id');
    $query->where('ss_fdffa.field_farm_asset_target_id = ' . $this->table_alias . '.id');
    $query->where('ss_fdffd.field_farm_date_value <= ' . REQUEST_TIME);
    $query->orderBy('ss_fdffd.field_farm_date_value', 'DESC');
    $query->orderBy('ss_log.id', 'DESC');
    $query->range(0, 1);
    $query->addField('ss_log', 'id');
    $join_extras[] = $this->alias . '.id = (' . $query . ')';

    // Implode the join extras into a single string for the query.
    $join->extra = implode(' AND ', $join_extras);

    // Add the join to the query.
    $this->date_alias = $this->query->add_relationship($alias, $join, $this->definition['base'], $this->relationship);

    // Add a condition to the query to filter out any empty dates (this will
    // filter out any logs that don't have the latest date).
    $this->query->add_where(0, $alias . '.field_farm_date_value', NULL, 'IS NOT NULL');
  }
}