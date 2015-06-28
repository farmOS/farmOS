<?php
/**
 * @file
 * Farm theme template.php.
 */

/**
 * Implements hook_form_alter().
 */
function farm_theme_form_alter(&$form, &$form_state, $form_id) {

  // Views Exposed (filters and sort) form:
  if ($form_id == 'views_exposed_form') {

    /* Wrap the exposed form in a Bootstrap collapsed panel. */

    // Form prefix HTML:
    $form['#prefix'] = '
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="' . $form['#id'] . '-panel-heading">
    <h4 class="panel-title">
      <a class="collapsed" data-toggle="collapse" href="#' . $form['#id'] . '-panel-body" aria-expanded="false" aria-controls="' . $form['#id'] . '-panel-body">
        Filter/Sort
      </a>
    </h4>
  </div>
  <div id="' . $form['#id'] . '-panel-body" class="panel-collapse collapse" role="tabpanel" aria-labelledby="' . $form['#id'] . '-panel-heading" aria-expanded="false" style="height: 0;">
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

  // If there is a farm images field, float it in the top left.
  if (!empty($build['field_farm_images'])) {

    // Wrap it in a floated div.
    $build['field_farm_images']['#prefix'] = '<div class="col-md-6">';
    $build['field_farm_images']['#suffix'] = '</div>';

    // Put everything else into another div and move it to the top so it
    // aligns left.
    $build['fields'] = array(
      '#prefix' => '<div class="col-md-6">',
      '#suffix' => '</div>',
      '#weight' => -100,
    );
    $elements = element_children($build);
    foreach ($elements as $element) {
      if (!in_array($element, array('field_farm_images', 'fields'))) {
        $build['fields'][$element] = $build[$element];
        unset($build[$element]);
      }
    }
  }
}

/**
 * Implements hook_preprocess_page().
 */
function farm_theme_preprocess_page(&$vars) {

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
