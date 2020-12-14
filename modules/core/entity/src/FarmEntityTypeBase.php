<?php

namespace Drupal\farm_entity;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\farm_field\FarmFieldFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a FarmEntityTypeBase for plugins to extends.
 */
abstract class FarmEntityTypeBase extends PluginBase implements ContainerFactoryPluginInterface {

  /**
   * The farm_field.factory service.
   *
   * @var \Drupal\farm_field\FarmFieldFactoryInterface
   */
  protected $farmFieldFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FarmFieldFactoryInterface $farm_field_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->farmFieldFactory = $farm_field_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('farm_field.factory')
    );
  }

}
