<?php

namespace Drupal\farm_ui_theme\Plugin\Block;

use Drupal\Component\Utility\SortArray;
use Drupal\Core\Menu\Plugin\Block\LocalActionsBlock;

/**
 * Provides a block to display the local actions.
 *
 * @Block(
 *   id = "farm_local_actions_block",
 *   admin_label = @Translation("Primary farm admin actions")
 * )
 */
class FarmLocalActionsBlock extends LocalActionsBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Render local actions as a dropbutton.
    $local_actions = parent::build();
    uasort($local_actions, [SortArray::class, 'sortByWeightProperty']);
    $links = [];
    foreach ($local_actions as $local_action) {
      if (!empty($local_action['#link']) && $local_action['#access']->isAllowed()) {

        // Copy localized_options into the URL options.
        // This is necessary to maintain any query parameters that were added
        // to the action links.
        $local_action['#link']['url']->setOptions(array_merge($local_action['#link']['url']->getOptions(), $local_action['#link']['localized_options']));

        // Add the link.
        $links[] = $local_action['#link'];
      }
    }
    return [
      '#type' => 'dropbutton',
      '#dropbutton_type' => 'standard',
      '#links' => $links,
      '#cache' => $local_actions['#cache'],
    ];
  }

}
