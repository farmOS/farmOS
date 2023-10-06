<?php

namespace Drupal\farm_quick\Plugin\QuickForm;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Interface for configurable quick forms.
 */
interface ConfigurableQuickFormInterface extends QuickFormInterface, ConfigurableInterface, PluginFormInterface {

}
