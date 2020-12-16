<?php

namespace Drupal\farm_ui_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Dashboard controller.
 *
 * @ingroup farm
 */
class DashboardController extends ControllerBase {

  /**
   * The layout plugin manager.
   *
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  protected $layoutPluginManager;

  /**
   * Class constructor.
   */
  public function __construct(LayoutPluginManagerInterface $layout_plugin_manager) {
    $this->layoutPluginManager = $layout_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.core.layout'));
  }

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
      $region = 'first';
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

      // If a region was provided, use it.
      if (!empty($pane['region'])) {
        $region = $pane['region'];
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
      $build[$region][$group][$id] = $container;
    }

    // Get the layout.
    $layoutInstance = $this->layoutPluginManager->createInstance('layout_twocol', []);

    // Define the regions.
    $region_names = ['top', 'first', 'second', 'bottom'];
    $regions = [];
    foreach ($region_names as $name) {
      $regions[$name] = !empty($build[$name]) ? $build[$name] : [];
    }

    // Build the layout.
    $render = $layoutInstance->build($regions);

    // Add the dashboard_layout styles.
    $render['#attached']['library'][] = 'farm_ui_dashboard/dashboard_layout';
    return $render;
  }

}
