<?php

namespace Drupal\farm_blocks\Plugin\Block;

use Drupal\system\Plugin\Block\SystemPoweredByBlock;

/**
 * Provides a 'Powered by farmOS' block.
 *
 * @Block(
 *   id = "farm_blocks_powered_by_block",
 *   admin_label = @Translation("Powered by farmOS")
 * )
 */
class FarmBlocksPoweredByBlock extends SystemPoweredByBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $translated = $this->t('Powered by <a href=":poweredby">farmOS</a>', array(':poweredby' => 'http://farmOS.org'));
    return array(
      '#markup' => '<span>' . $translated . '</span>',
    );
  }

}
