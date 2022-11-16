<?php

namespace Drupal\farm_update;

use Drupal\config_update\ConfigDiffer;
use Drupal\config_update\ConfigListerWithProviders;
use Drupal\config_update\ConfigReverter;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Psr\Log\LoggerInterface;

/**
 * Farm update service.
 *
 * @internal
 */
class FarmUpdate implements FarmUpdateInterface {

  use StringTranslationTrait;

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The config differ.
   *
   * @var \Drupal\config_update\ConfigDiffer
   */
  protected $configDiff;

  /**
   * The config lister.
   *
   * @var \Drupal\config_update\ConfigListerWithProviders
   */
  protected $configList;

  /**
   * The config reverter.
   *
   * @var \Drupal\config_update\ConfigReverter
   */
  protected $configUpdate;

  /**
   * Constructs a FarmUpdate object.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\config_update\ConfigDiffer $config_diff
   *   The config differ.
   * @param \Drupal\config_update\ConfigListerWithProviders $config_list
   *   The config lister.
   * @param \Drupal\config_update\ConfigReverter $config_update
   *   The config reverter.
   */
  public function __construct(LoggerInterface $logger, ModuleHandlerInterface $module_handler, EntityTypeManagerInterface $entity_manager, ConfigFactoryInterface $config_factory, ConfigDiffer $config_diff, ConfigListerWithProviders $config_list, ConfigReverter $config_update) {
    $this->logger = $logger;
    $this->moduleHandler = $module_handler;
    $this->entityManager = $entity_manager;
    $this->configFactory = $config_factory;
    $this->configDiff = $config_diff;
    $this->configList = $config_list;
    $this->configUpdate = $config_update;
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(): void {

    // Get a list of excluded config.
    $exclude_config = $this->getExcludedItems();

    // Build a list of config to revert, without excluded config.
    $revert_config = array_diff($this->getDifferentItems('type', 'system.all'), $exclude_config);

    // Iterate through config items and revert them.
    foreach ($revert_config as $name) {

      // Get the config type and bail if simple configuration.
      // The lister gives NULL if simple configuration.
      $type = $this->configList->getTypeNameByConfigName($name);
      if ($type === NULL) {
        continue;
      }

      // Get the config short name.
      $shortname = $this->getConfigShortname($type, $name);

      // Perform the operation.
      $result = $this->configUpdate->revert($type, $shortname);

      // Log the result.
      if ($result) {
        $this->logger->notice('Reverted config: @config', ['@config' => $name]);
      }
      else {
        $this->logger->error('Failed to revert config: @config', ['@config' => $name]);
      }
    }
  }

  /**
   * Lists excluded config items.
   *
   * Lists config items that should be excluded from all automatic updates.
   *
   * @return array
   *   An array of config item names.
   */
  protected function getExcludedItems() {

    // Ask modules for config exclusions.
    $exclude_config = $this->moduleHandler->invokeAll('farm_update_exclude_config');

    // Load farm_update.settings to get additional exclusions.
    $settings_exclude_config = $this->configFactory->get('farm_update.settings')->get('exclude_config');
    if (!empty($settings_exclude_config)) {
      $exclude_config = array_merge($exclude_config, $settings_exclude_config);
    }

    // Always exclude this module's configuration.
    // This isn't strictly necessary because we don't provide default config
    // in config/install. But in the unlikely event that a custom module does
    // provide this config, and then it is somehow overridden by another means,
    // it would be reverted. So we exclude it here just to be extra safe.
    $exclude_config[] = 'farm_update.settings';

    return $exclude_config;
  }

  /**
   * Lists differing config items.
   *
   * Lists config items that differ from the versions provided by your
   * installed modules, themes, or install profile. See config-diff to show
   * what the differences are.
   *
   * This method is copied directly from ConfigUpdateUiCliService.
   *
   * @param string $type
   *   Run the report for: module, theme, profile, or "type" for config entity
   *   type.
   * @param string $name
   *   The machine name of the module, theme, etc. to report on. See
   *   config-list-types to list types for config entities; you can also use
   *   system.all for all types, or system.simple for simple config.
   *
   * @return array
   *   An array of differing configuration items.
   *
   * @see \Drupal\config_update_ui\ConfigUpdateUiCliService::getDifferentItems()
   */
  protected function getDifferentItems($type, $name) {
    [$activeList, $installList, $optionalList] = $this->configList->listConfig($type, $name);
    $addedItems = array_diff($activeList, $installList, $optionalList);
    $activeAndAddedItems = array_diff($activeList, $addedItems);
    $differentItems = [];
    foreach ($activeAndAddedItems as $name) {
      $active = $this->configUpdate->getFromActive('', $name);
      $extension = $this->configUpdate->getFromExtension('', $name);
      if (!$this->configDiff->same($active, $extension)) {
        $differentItems[] = $name;
      }
    }
    sort($differentItems);

    return $differentItems;
  }

  /**
   * Gets the config item shortname given the type and name.
   *
   * This method is copied directly from ConfigUpdateUiCliService.
   *
   * @param string $type
   *   The type of the config item.
   * @param string $name
   *   The name of the config item.
   *
   * @return string
   *   The shortname for the configuration item.
   *
   * @see \Drupal\config_update_ui\ConfigUpdateUiCliService::getConfigShortname()
   */
  protected function getConfigShortname($type, $name) {
    $shortname = $name;
    if ($type != 'system.simple') {
      $definition = $this->entityManager->getDefinition($type);
      $prefix = $definition->getConfigPrefix() . '.';
      if (strpos($name, $prefix) === 0) {
        $shortname = substr($name, strlen($prefix));
      }
    }

    return $shortname;
  }

}
