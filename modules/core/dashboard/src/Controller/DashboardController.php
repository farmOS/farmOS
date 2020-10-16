<?php

namespace Drupal\farm_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;

/**
 * Dashboard controller.
 *
 * @ingroup farm
 */
class DashboardController extends ControllerBase {

  /**
   * Builds the farm dashboard page.
   *
   * @return array
   *   Render array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function dashboard() {

    // Start a build array.
    $build = [];

    // Ask modules for dashboard panes.
    $panes = $this->moduleHandler()->invokeAll('farm_dashboard_panes');

    // Add each pane to the dashboard.
    foreach ($panes as $id => $pane) {

      // Set default values.
      $args = [];
      $output = '';
      $title = '';
      $group = 'default';
      $weight = 0;

      // Use any provided args.
      if (!empty($pane['args'])) {
        $args = $pane['args'];
      }

      // If 'view' and 'view_display_id' are provided, display the view.
      if (!empty($pane['view']) && !empty($pane['view_display_id'])) {

        // Load the view. Note that this
        // returns a special ViewExecutable object, not a View entity.
        $view = Views::getView($pane['view']);

        // Skip if the view doesn't exist or access check fails.
        if (empty($view) || !$view->access($pane['view_display_id'])) {
          continue;
        }

        // Set the display so we get the correct title.
        $view->setDisplay($pane['view_display_id']);

        // Set the title.
        $title = $view->getTitle();

        // Build the views renderable output.
        $output = $view->buildRenderable($pane['view_display_id'], $args);
      }

      // Or if a block is provided, display the block.
      elseif (!empty($pane['block'])) {

        /** @var \Drupal\block\Entity\Block $block */
        $block = $this->entityTypeManager()->getStorage('block')->load($pane['block']);

        // Set the block plugin config if provided.
        if (!empty($args)) {
          $block->getPlugin()->setConfiguration($args);
        }

        // If the block plugin displays the label by default, set the title.
        $block_config = $block->getPlugin()->getConfiguration();
        if ($block_config['label_display']) {
          $title = $block->label();
        }

        // Use the block's weight by default.
        $weight = $block->getWeight();

        // Build the blocks renderable output.
        $output = $this->entityTypeManager()->getViewBuilder('block')->view($block);
      }

      // If a specific title was provided, use it.
      if (!empty($pane['title'])) {
        $title = $pane['title'];
      }

      // If a group was provided, use it.
      if (!empty($pane['group'])) {
        $group = $pane['group'];
      }

      // If a weight was provided, use it.
      if (!empty($pane['weight'])) {
        $weight = $pane['weight'];
      }

      // Create a container for the dashboard pane.
      $container = [
        '#type' => 'container',
        '#weight' => $weight,
      ];

      // If a title is set, make it a fieldset.
      if (!empty($title)) {
        $container['#type'] = 'fieldset';
        $container['#title'] = $title;
      }

      // Include output inside the container.
      $container[] = $output;

      // Add the container to the build array.
      $build[$group][$id] = $container;
    }

    return $build;
  }

}
