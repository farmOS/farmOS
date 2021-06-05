<?php

namespace Drupal\farm_group;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_location\AssetLocation;
use Drupal\farm_location\AssetLocationInterface;
use Drupal\farm_location\LogLocationInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Asset location logic, accounting for group membership.
 */
class GroupAssetLocation extends AssetLocation implements AssetLocationInterface {

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected GroupMembershipInterface $groupMembership;

  /**
   * Class constructor.
   *
   * @param \Drupal\farm_location\LogLocationInterface $log_location
   *   Log location service.
   * @param \Drupal\farm_log\LogQueryFactoryInterface $log_query_factory
   *   Log query factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   The group membership service.
   */
  public function __construct(LogLocationInterface $log_location, LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time, Connection $database, GroupMembershipInterface $group_membership) {
    parent::__construct($log_location, $log_query_factory, $entity_type_manager, $time, $database);
    $this->groupMembership = $group_membership;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('log.location'),
      $container->get('farm.log_query'),
      $container->get('entity_type.manager'),
      $container->get('datetime.time'),
      $container->get('database'),
      $container->get('group.membership')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getMovementLog(AssetInterface $asset): ?LogInterface {

    // Delegate to the parent service to get the latest movement log that
    // references this asset.
    $latest_movement = parent::getMovementLog($asset);

    // Remember the latest movement id and timestamp.
    $latest_id = 0;
    $latest_timestamp = 0;
    if (!empty($latest_movement)) {
      $latest_id = $latest_movement->id();
      $latest_timestamp = $latest_movement->get('timestamp')->value;
    }

    // Get the groups that this asset is assigned to.
    $groups = $this->groupMembership->getGroup($asset);

    // If there are groups, iterate through them.
    if (!empty($groups)) {
      foreach ($groups as $group) {

        // Get the latest movement log that references the group.
        $group_movement = parent::getMovementLog($group);

        // If the group doesn't have a movement, skip it.
        if (empty($group_movement)) {
          continue;
        }

        // If the group's movement is the latest, replace the latest movement.
        $group_movement_id = $group_movement->id();
        $group_movement_timestamp = $group_movement->get('timestamp')->value;
        if (($group_movement_id > $latest_id) && ($group_movement_timestamp >= $latest_timestamp)) {
          $latest_movement = $group_movement;
          $latest_id = $group_movement_id;
          $latest_timestamp = $group_movement_timestamp;
        }
      }
    }

    // Return the latest movement log.
    return $latest_movement;
  }

  /**
   * {@inheritdoc}
   */
  public function getAssetsByLocation(AssetInterface $location): array {

    // First delegate to the parent function to get assets in the location.
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = parent::getAssetsByLocation($location);

    // Iterate through the assets to check if any of them are groups.
    $groups = [];
    foreach ($assets as $asset) {
      if ($asset->bundle() == 'group') {
        $groups[] = $asset;
      }
    }

    // Recursively load all group members and add them to the list of assets.
    /** @var \Drupal\asset\Entity\AssetInterface[] $members */
    $members = [];
    foreach ($groups as $group) {
      $members = array_merge($members, $this->groupMembership->getGroupMembers($group, TRUE));
    }
    $assets = array_merge($assets, $members);

    // It is possible for a group member asset to be in a different location
    // than the group, if it has a movement log that is more recent than the
    // group's. So iterate through all the assets and remove any that are not in
    // the location. The asset may be in multiple locations (including this
    // one), so we only want to remove it if none of its locations match.
    foreach ($assets as $key => $asset) {
      $match = FALSE;
      foreach ($this->getLocation($asset) as $asset_location) {
        if ($asset_location->id() == $location->id()) {
          $match = TRUE;
        }
      }
      if (!$match) {
        unset($assets[$key]);
      }
    }

    // Return the assets.
    return $assets;
  }

}
