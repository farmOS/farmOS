<?php

namespace Drupal\farm_quick\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\farm_quick\QuickFormPluginCollection;

/**
 * Defines the quick form instance config entity.
 *
 * @ConfigEntityType(
 *   id = "quick_form",
 *   label = @Translation("Quick form"),
 *   label_collection = @Translation("Quick forms"),
 *   label_singular = @Translation("quick form"),
 *   label_plural = @Translation("quick forms"),
 *   label_count = @PluralTranslation(
 *     singular = "@count quick form",
 *     plural = "@count quick forms",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\EntityAccessControlHandler",
 *     "permission_provider" = "\Drupal\entity\EntityPermissionProvider",
 *     "form" = {
 *       "configure" = "Drupal\farm_quick\Form\ConfigureQuickForm",
 *     },
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "status" = "status"
 *   },
 *   config_export = {
 *     "id",
 *     "plugin",
 *     "label",
 *     "description",
 *     "helpText",
 *     "settings",
 *   },
 * )
 */
class QuickFormInstance extends ConfigEntityBase implements QuickFormInstanceInterface, EntityWithPluginCollectionInterface {

  /**
   * The ID of the quick form instance.
   *
   * @var string
   */
  protected $id;

  /**
   * The plugin instance ID.
   *
   * @var string
   */
  protected $plugin;

  /**
   * The plugin collection that holds the quick form plugin for this entity.
   *
   * @var \Drupal\farm_quick\QuickFormPluginCollection
   */
  protected $pluginCollection;

  /**
   * The quick form label.
   *
   * @var string
   */
  protected $label;

  /**
   * A brief description of the quick form.
   *
   * @var string
   */
  protected $description;

  /**
   * Help text for the quick form.
   *
   * @var string
   */
  protected $helpText;

  /**
   * The plugin instance settings.
   *
   * @var array
   */
  protected $settings = [];

  /**
   * {@inheritdoc}
   */
  public function getPlugin() {
    return $this->getPluginCollection()->get($this->plugin);
  }

  /**
   * Encapsulates the creation of the farm_quick's plugin collection.
   *
   * @return \Drupal\Component\Plugin\LazyPluginCollection
   *   The block's plugin collection.
   */
  protected function getPluginCollection() {
    if (!$this->pluginCollection) {
      $this->pluginCollection = new QuickFormPluginCollection(\Drupal::service('plugin.manager.quick_form'), $this->plugin, $this->get('settings'), $this->id());
    }
    return $this->pluginCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [
      'settings' => $this->getPluginCollection(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginId() {
    return $this->plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label ?? $this->getPlugin()->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description ?? $this->getPlugin()->getDescription();
  }

  /**
   * {@inheritdoc}
   */
  public function getHelpText() {
    return $this->helpText ?? $this->getPlugin()->getHelpText();
  }

  /**
   * {@inheritdoc}
   */
  public function getSettings() {
    return $this->settings;
  }

}
