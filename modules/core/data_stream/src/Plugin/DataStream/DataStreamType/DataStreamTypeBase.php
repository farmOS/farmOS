<?php

namespace Drupal\data_stream\Plugin\DataStream\DataStreamType;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base data stream type class.
 */
abstract class DataStreamTypeBase extends PluginBase implements ContainerFactoryPluginInterface, DataStreamTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    return [];
  }

}
