<?php

namespace Drupal\farm_group;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Override the asset.location service with our own class.
 */
class FarmGroupServiceProvider extends ServiceProviderBase implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('asset.location');
    $definition->addArgument(new Reference('group.membership'));
    $definition->setClass('Drupal\farm_group\GroupAssetLocation');
  }

}
