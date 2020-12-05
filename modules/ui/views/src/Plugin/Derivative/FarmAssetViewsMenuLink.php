<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

/**
 * Provides menu links for farmOS Asset Views.
 */
class FarmAssetViewsMenuLink extends FarmViewsMenuLink {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'asset';

}
