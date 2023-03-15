<?php

namespace Drupal\farm_quick;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;

/**
 * Provides a collection of quick form plugins.
 */
class QuickFormPluginCollection extends DefaultSingleLazyPluginCollection {

  /**
   * The quick form ID this plugin collection belongs to.
   *
   * @var string
   */
  protected $quickFormId;

  /**
   * Constructs a new QuickFormPluginCollection.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $manager
   *   The manager to be used for instantiating plugins.
   * @param string $instance_id
   *   The ID of the plugin instance.
   * @param array $configuration
   *   An array of configuration.
   * @param string $quick_form_id
   *   The unique ID of the quick form entity using this plugin.
   */
  public function __construct(PluginManagerInterface $manager, $instance_id, array $configuration, $quick_form_id) {
    parent::__construct($manager, $instance_id, $configuration);
    $this->quickFormId = $quick_form_id;
  }

  /**
   * {@inheritdoc}
   */
  protected function initializePlugin($instance_id) {
    if (!$instance_id) {
      throw new PluginException("The quick form '{$this->quickFormId}' did not specify a plugin.");
    }
    parent::initializePlugin($instance_id);
  }

}
