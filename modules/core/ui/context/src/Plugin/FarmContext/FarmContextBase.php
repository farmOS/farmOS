<?php

namespace Drupal\farm_ui_context\Plugin\FarmContext;

use Drupal\Core\Plugin\ContextAwarePluginAssignmentTrait;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Plugin\ContextAwarePluginTrait;
use Drupal\Core\Plugin\PluginBase;

/**
 * Base implementation of the FarmContext plugin.
 */
abstract class FarmContextBase extends PluginBase implements FarmContextInterface, ContextAwarePluginInterface {

  use ContextAwarePluginTrait;
  use ContextAwarePluginAssignmentTrait;

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

}
