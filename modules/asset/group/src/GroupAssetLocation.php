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
  public function getMovementLog(AssetInterface $asset, $timestamp = NULL): ?LogInterface {

    // Delegate to the parent service to get the latest movement log that
    // references this asset.
    $latest_movement = parent::getMovementLog($asset, $timestamp);

    // Remember the latest movement id and timestamp.
    $latest_id = 0;
    $latest_timestamp = 0;
    if (!empty($latest_movement)) {
      $latest_id = $latest_movement->id();
      $latest_timestamp = $latest_movement->get('timestamp')->value;
    }

    // Get the groups that this asset is assigned to.
    $groups = $this->groupMembership->getGroup($asset, $timestamp);

    // If there are groups, iterate through them.
    if (!empty($groups)) {
      foreach ($groups as $group) {

        // Get the latest movement log that references the group.
        $group_movement = parent::getMovementLog($group, $timestamp);

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
  public function getAssetsByLocation(array $locations, $timestamp = NULL): array {

    // First delegate to the parent function to get assets in the location.
    /** @var \Drupal\asset\Entity\AssetInterface[] $assets */
    $assets = parent::getAssetsByLocation($locations, $timestamp);

    // Recursively load all group members and add them to the list of assets.
    $groups = array_filter($assets, function (AssetInterface $asset) {
      return $asset->bundle() === 'group';
    });
    $assets += $this->groupMembership->getGroupMembers($groups, TRUE, $timestamp);

    // Get location ids.
    $location_ids = array_map(function (AssetInterface $location) {
      return $location->id();
    }, $locations);

    // It is possible for a group member asset to be in a different location
    // than the group, if it has a movement log that is more recent than the
    // group's. So iterate through all the assets and remove any that are not in
    // one of the specified locations. The asset may be in multiple locations
    // (including this one), so we only want to remove it if none of its
    // locations match.
    $assets = array_filter($assets, function (AssetInterface $asset) use ($location_ids, $timestamp) {

      // Get asset location ids.
      $asset_location_ids = array_map(function (AssetInterface $location) {
        return $location->id();
      }, $this->getLocation($asset, $timestamp));

      // Only include the asset if it is in one of the specified locations.
      return !empty(array_intersect($location_ids, $asset_location_ids));
    });

    // Return the assets.
    return $assets;
  }

}
