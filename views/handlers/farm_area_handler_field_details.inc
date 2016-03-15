<?php

/**
 * @file
 * Field handler to present farm area links.
 */

/**
 * Field handler to present farm area details.
 */
class farm_area_handler_field_details extends views_handler_field {

  /**
   * {@inheritdoc}
   */
  public function construct() {

    // Inherit the parent's construction.
    parent::construct();

    // Add the term id as an additional field to load to ensure that it is
    // available in this handler.
    $this->additional_fields['tid'] = 'tid';
  }

  /**
   * {@inheritdoc}
   */
  public function query() {

    // Ensure the main table for this field is included.
    $this->ensure_my_table();

    // Include additional fields (like term id defined in $this->construct())
    $this->add_additional_fields();
  }

  /**
   * {@inheritdoc}
   */
  public function render($values) {

    // Load the term id value.
    $aid = $this->get_value($values, 'tid');

    // Generate a simple empty DIV that will be populated via the Javascript.
    $output = '<div class="area-details" id="area-details-' . $aid . '"></div>';

    // Return the details.
    return $output;
  }

}
