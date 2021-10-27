<?php

namespace Drupal\farm_group\Plugin\views\argument;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_group\GroupMembershipInterface;
use Drupal\views\Plugin\views\argument\ArgumentPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An argument for filtering assets by their current group.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("asset_group")
 */
class AssetGroup extends ArgumentPluginBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

  /**
   * Constructs an AssetGroup object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   The group membership service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, GroupMembershipInterface $group_membership) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->groupMembership = $group_membership;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('group.membership'),
    );
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\farm_location\Plugin\views\argument\AssetLocation
   */
  public function query($group_by = FALSE) {

    // First query for a list of asset IDs in the group, then use this list to
    // filter the current View.
    // We do this in two separate queries for a few reasons:
    // 1. The Drupal and Views query APIs do not support the kind of compound
    // JOIN that we use in the group.membership service's getMembers().
    // 2. We need to allow other modules to override the group.membership
    // service to provide their own location logic. They shouldn't have to
    // override this Views argument handler as well.
    // 3. It keeps this Views argument handler's query modifications very
    // simple. It only needs the condition: "WHERE asset.id IN (:asset_ids)".
    // See https://www.drupal.org/project/farm/issues/3217184 for more info.
    $group = $this->entityTypeManager->getStorage('asset')->load($this->argument);
    $assets = $this->groupMembership->getGroupMembers([$group]);
    $asset_ids = [];
    foreach ($assets as $asset) {
      $asset_ids[] = $asset->id();
    }

    // If there are no asset IDs, add 0 to ensure the array is not empty.
    if (empty($asset_ids)) {
      $asset_ids[] = 0;
    }

    // Filter to only include assets with those IDs.
    $this->ensureMyTable();
    $this->query->addWhere(0, "$this->tableAlias.id", $asset_ids, 'IN');
  }

}
