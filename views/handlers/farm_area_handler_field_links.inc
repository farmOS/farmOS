<?php

/**
 * @file
 * Field handler to present farm area links.
 */
class farm_area_handler_field_links extends views_handler_field {

  // Construct the handler.
  function construct() {

    // Inherit the parent's construction.
    parent::construct();

    // Add the term id as an additional field to load to ensure that it is available in this handler.
    $this->additional_fields['tid'] = 'tid';
  }

  // Add fields to the query.
  function query() {

    // Ensure the main table for this field is included.
    $this->ensure_my_table();

    // Include additional fields (like term id defined in $this->construct())
    $this->add_additional_fields();
  }

  // Render the field.
  function render($values) {

    // Load the term id value.
    $aid = $this->get_value($values, 'tid');

    // Generate the area links.
    $links = farm_area_get_links($aid);

    // Return the links as a single string, filtered for XSS.
    return filter_xss(implode(' | ', $links));
  }
}