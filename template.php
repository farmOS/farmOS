<?php
/**
 * @file
 * Farm theme template.php.
 */

/**
 * Implements hook_form_alter().
 */
function farm_theme_form_alter(&$form, &$form_state, $form_id) {

  // Log form:
  if ($form_id == 'log_form') {

    // Movement logs:
    if ($form['#bundle'] == 'farm_movement') {

      // Collapse the geofield fieldset by default.
      if (!empty($form['field_farm_geofield'][LANGUAGE_NONE][0])) {
        $form['field_farm_geofield'][LANGUAGE_NONE][0]['#collapsible'] = TRUE;
        $form['field_farm_geofield'][LANGUAGE_NONE][0]['#collapsed'] = TRUE;
      }
    }
  }

  // Views Exposed (filters and sort) form:
  elseif ($form_id == 'views_exposed_form') {

    /* Wrap the exposed form in a Bootstrap collapsible panel. */

    // Collapsible panel ID.
    $panel_head_id = $form['#id'] . '-panel-heading';
    $panel_body_id = $form['#id'] . '-panel-body';

    // Collapse by default.
    $collapse = TRUE;

    // If the form was submitted (if there are values in $_GET other than 'q'),
    // do not collapse the form.
    if (count($_GET) > 1) {
      $collapse = FALSE;
    }

    // Set attributes depending on the collapsed state (used in HTML below).
    if ($collapse) {
      $collapse_class = '';
      $aria_expanded = 'false';
    } else {
      $collapse_class = ' in';
      $aria_expanded = 'true';
    }

    // Form prefix HTML:
    $form['#prefix'] = '
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="' . $panel_head_id . '">
    <h4 class="panel-title">
      <a data-toggle="collapse" href="#' . $panel_body_id . '" aria-expanded="' . $aria_expanded . '" aria-controls="' . $panel_body_id . '">
        Filter/Sort
      </a>
    </h4>
  </div>
  <div id="' . $panel_body_id . '" class="panel-collapse collapse' . $collapse_class . '" role="tabpanel" aria-labelledby="' . $panel_head_id . '">
    <div class="panel-body">';

    // Form suffix HTML:
    $form['#suffix'] = '
    </div>
  </div>
</div>';
  }
}

/**
 * Implements hook_views_bulk_operations_form_alter().
 */
function farm_theme_views_bulk_operations_form_alter(&$form, &$form_state, $vbo) {

  // Add some JavaScript to hide the VBO buttons until items are selected.
  drupal_add_js(drupal_get_path('theme', 'farm_theme') . '/js/vbo.js');

  // Move VBO buttons to the bottom.
  $form['select']['#weight'] = 100;

  // Move the "Clone" action to the end of the list.
  if (!empty($form['select']['action::log_clone_action'])) {
    $form['select']['action::log_clone_action']['#weight'] = 100;
  }

  // Add Bootstrap classes to the action buttons.
  $buttons = array(
    'farm_log_asset_move' => 'primary',
    'log_done' => 'success',
    'log_undone' => 'danger',
    'log_reschedule' => 'warning',
    'log_clone' => 'primary',
  );
  foreach ($buttons as $name => $style) {
    $action_name = 'action::' . $name . '_action';
    if (!empty($form['select'][$action_name])) {
      $form['select'][$action_name]['#attributes']['class'][] = 'btn-' . $style;
    }
  }
}

/**
 * Implements hook_entity_view_alter().
 */
function farm_theme_entity_view_alter(&$build, $type) {

  // If the entity is not a farm_asset, bail.
  if ($type != 'farm_asset') {
    return;
  }

  // Float the location information to the right.
  if (!empty($build['location'])) {

    // Wrap it in a floated div.
    $build['location']['#prefix'] = '<div class="col-md-6">';
    $build['location']['#suffix'] = '</div>';
    $build['location']['#weight'] = -99;

    // Put everything else into another div and move it to the top so it
    // aligns left.
    $build['fields'] = array(
      '#prefix' => '<div class="col-md-6">',
      '#suffix' => '</div>',
      '#weight' => -100,
    );
    $elements = element_children($build);
    foreach ($elements as $element) {
      if (!in_array($element, array('location', 'fields', 'views'))) {
        $build['fields'][$element] = $build[$element];
        unset($build[$element]);
      }
    }

    // Wrap the Views in a full-width div at the bottom.
    if (!empty($build['views'])) {
      $build['views']['#prefix'] = '<div class="col-md-12">';
      $build['views']['#suffix'] = '</div>';
      $build['views']['#weight'] = 101;
    }
  }
}

/**
 * Implements hook_preprocess_page().
 */
function farm_theme_preprocess_page(&$vars) {

  // When the farm_areas map is displayed on a page...
  if (!empty($vars['page']['content']['farm_areas'])) {

    // Wrap map and content in "col-md-6" class so they display in two columns.
    $vars['page']['content']['farm_areas']['#prefix'] = '<div class="col-md-6">';
    $vars['page']['content']['farm_areas']['#suffix'] = '</div>';
    $vars['page']['content']['system_main']['#prefix'] = '<div class="col-md-6">';
    $vars['page']['content']['system_main']['#suffix'] = '</div>';
  }

  // Remove from taxonomy term pages:
  // "There is currently no content classified with this term."
  if (isset($vars['page']['content']['system_main']['no_content'])) {
    unset($vars['page']['content']['system_main']['no_content']);
  }

  // Add "Powered by farmOS" to the footer.
  $vars['page']['footer']['farmos'] = array(
    '#type' => 'markup',
    '#prefix' => '<div style="text-align: center;"><small>',
    '#markup' => t('Powered by') . ' ' . l(t('farmOS'), 'http://farmos.org'),
    '#suffix' => '</small></div>',
  );
}

/**
 * Implements hook_preprocess_field().
 */
function farm_theme_preprocess_field(&$vars) {

  // Add a clearfix class to field_farm_images to prevent float issues.
  // @see .field-name-field-farm-images .field-item in styles.css.
  if ($vars['element']['#field_name'] == 'field_farm_images') {
    $vars['classes_array'][] = 'clearfix';
  }
}
