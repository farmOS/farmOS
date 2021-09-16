<?php

namespace Drupal\farm_update;

use Drupal\config_update\ConfigDiffer;
use Drupal\config_update\ConfigListerWithProviders;
use Drupal\config_update\ConfigReverter;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Farm update service.
 */
class FarmUpdate implements FarmUpdateInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

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
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity type manager.
   * @param \Drupal\config_update\ConfigDiffer $config_diff
   *   The config differ.
   * @param \Drupal\config_update\ConfigListerWithProviders $config_list
   *   The config lister.
   * @param \Drupal\config_update\ConfigReverter $config_update
   *   The config reverter.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, ConfigDiffer $config_diff, ConfigListerWithProviders $config_list, ConfigReverter $config_update) {
    $this->entityManager = $entity_manager;
    $this->configDiff = $config_diff;
    $this->configList = $config_list;
    $this->configUpdate = $config_update;
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(): void {

    // Build a list of config to revert.
    $revert_config = $this->getDifferentItems('type', 'system.all');

    // Iterate through config items and revert them.
    foreach ($revert_config as $name) {

      // Get the config type.
      // The lister gives NULL if simple configuration, but the reverter
      // expects 'system.simple' so we convert it.
      $type = $this->configList->getTypeNameByConfigName($name);
      if ($type === NULL) {
        $type = 'system.simple';
      }

      // Get the config short name.
      $shortname = $this->getConfigShortname($type, $name);

      // Perform the operation.
      $this->configUpdate->revert($type, $shortname);
    }
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
