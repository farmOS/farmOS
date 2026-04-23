<?php

declare(strict_types=1);

namespace Drupal\farm_ui_search\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Search assets' block.
 */
#[Block(
  id: 'farm_asset_search_block',
  admin_label: new TranslatableMarkup('Asset Search'),
)]
class AssetSearchBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected FormBuilderInterface $formBuilder,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermissions($account, ['access asset collection']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      'asset_search' => $this->formBuilder->getForm('Drupal\farm_ui_search\Form\AssetSearchForm'),
    ];
  }

}
