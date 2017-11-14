<?php

/**
 * @file
 * Farm asset inventory value field handler.
 */

/**
 * Filter class which allows to filter by certain bundles of an entity.
 *
 * This extends the core Views views_handler_filter_entity_bundle class and
 * filters the options to only include asset types that have inventory enabled.
 */
class farm_inventory_handler_field_asset_inventory_value extends views_handler_field {

  /**
   * {@inheritdoc}
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
   * {@inheritdoc}
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
   * {@inheritdoc}
   */
  public function query() {
    $this->ensure_my_table();

    // Build a sub-query that will be used in the join to calculate the asset's
    // current inventory level.
    $asset_id_field = $this->table_alias . '.id';
    $done = !empty($this->options['done']) ? TRUE : FALSE;
    $future = !empty($this->options['future']) ? TRUE : FALSE;
    if ($future) {
      $time = 0;
    }
    else {
      $time = REQUEST_TIME;
    }
    $query = farm_inventory_query($asset_id_field, $time, $done);

    // Add the inventory value field.
    $params = $this->options['group_type'] != 'group' ? array('function' => $this->options['group_type']) : array();
    $this->field_alias = $this->query->add_field(NULL, '(' . $query . ')', 'asset_inventory', $params);

    $this->add_additional_fields();

    // Join in the {farm_inventory_asset_type} table and add the "individual"
    // field so we can see if we need to treat assets as individuals.
    $join = new views_join();
    $join->construct('farm_inventory_asset_type', $this->table_alias, 'type', 'type');
    $this->farm_inventory_asset_type_table = $this->query->ensure_table('farm_inventory_asset_type', $this->relationship, $join);
    $this->individual_field = $this->query->add_field($this->farm_inventory_asset_type_table, 'individual');

  }

  /**
   * {@inheritdoc}
   */
  function render($values) {

    // Get the inventory value.
    $value = $this->get_value($values);

    // Get the "individual" setting.
    $individual = $values->{$this->individual_field};

    // If the value is null, and this asset should be treated as an individual,
    // set the inventory to 1.
    if (is_null($value) && !empty($individual)) {
      return '1';
    }

    // Otherwise, return the formatted inventory string.
    else {
      return farm_inventory_format($value);
    }
  }
}
