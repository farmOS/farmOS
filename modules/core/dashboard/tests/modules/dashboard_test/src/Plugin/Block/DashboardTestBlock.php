<?php

namespace Drupal\farm_dashboard_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Powered by farmOS' block.
 *
 * @Block(
 *   id = "dashboard_test_block",
 *   admin_label = @Translation("Dashboard test block")
 * )
 */
class DashboardTestBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return ['#markup' => '<span>' . $this->t('This is the dashboard test block.') . '</span>'];
  }

}
