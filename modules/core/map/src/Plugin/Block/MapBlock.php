<?php

namespace Drupal\farm_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a map block.
 *
 * @Block(
 *   id = "map_block",
 *   admin_label = @Translation("Map block"),
 * )
 */
class MapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'farm_map',
      '#map_type' => $this->mapType(),
    ];
  }

  /**
   * Function that returns the map type.
   *
   * @return string
   *   The map type.
   */
  public function mapType() {

    // Use the map_type from the block configuration.
    if (!empty($this->configuration['map_type'])) {
      return $this->configuration['map_type'];
    }

    // Else default to 'default'.
    return 'default';
  }

}
