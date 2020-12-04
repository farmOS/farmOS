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
      '#map_name' => $this->mapName(),
    ];
  }

  /**
   * Function that returns the map name.
   *
   * @return string
   *   The map ID.
   */
  public function mapName() {

    // Use the ID from the block configuration.
    if (!empty($this->configuration['map_name'])) {
      return $this->configuration['map_name'];
    }

    // Else default to 'farm_map'.
    return 'farm_map';
  }

}
