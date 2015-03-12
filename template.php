<?php
/**
 * @file
 * Farm theme template.php
 */

/**
 * Implements hook_form_alter().
 */
function farm_theme_form_alter(&$form, &$form_state, $form_id) {

  // Views Exposed (filters and sort) form:
  if ($form_id == 'views_exposed_form') {

    /**
     * Wrap the exposed form in a Bootstrap collapsed panel.
     */

    // Form prefix HTML:
    $form['#prefix'] = '
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="ViewsFilterSortHeading">
    <h4 class="panel-title">
      <a class="collapsed" data-toggle="collapse" href="#ViewsFilterSort" aria-expanded="false" aria-controls="ViewsFilterSort">
        Filter/Sort
      </a>
    </h4>
  </div>
  <div id="ViewsFilterSort" class="panel-collapse collapse" role="tabpanel" aria-labelledby="ViewsFilterSortHeading" aria-expanded="false" style="height: 0px;">
    <div class="panel-body">';

    // Form suffix HTML:
    $form['#suffix'] = '
    </div>
  </div>
</div>';
  }

  // Views Bulk Operations form:
  else if (strpos($form_id, 'views_form_') === 0 && !empty($form['select'])) {

    // Add some JavaScript to hide the VBO buttons until items are selected.
    drupal_add_js(drupal_get_path('theme', 'farm_theme') . '/js/vbo.js');

    // Move VBO buttons to the bottom.
    $form['select']['#weight'] = 100;
  }
}
