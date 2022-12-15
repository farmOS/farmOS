<?php

namespace Drupal\farm_group;

use Drupal\asset\Entity\AssetInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Asset group membership logic.
 */
interface GroupMembershipInterface {

  /**
   * Check if an asset is a member of a group.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return bool
   *   Returns TRUE if the asset is a member of a group, FALSE otherwise.
   */
  public function hasGroup(AssetInterface $asset, $timestamp = NULL): bool;

  /**
   * Get group assets that an asset is a member of.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return array
   *   Returns an array of assets.
   */
  public function getGroup(AssetInterface $asset, $timestamp = NULL): array;

  /**
   * Find the latest group assignment log that references an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\log\Entity\LogInterface|null
   *   A log entity, or NULL if no logs were found.
   */
  public function getGroupAssignmentLog(AssetInterface $asset, $timestamp = NULL): ?LogInterface;

  /**
   * Get assets that are members of groups.
   *
   * @param \Drupal\asset\Entity\AssetInterface[] $groups
   *   An array of group assets to lookup.
   * @param bool $recurse
   *   Boolean: whether or not to recurse and load members of sub-groups.
   *   Defaults to TRUE.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   An array of asset objects indexed by their IDs.
   */
  public function getGroupMembers(array $groups, bool $recurse = TRUE, $timestamp = NULL): array;

}
