<?php

namespace Drupal\farm_group;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\farm_location\AssetLocation;
use Drupal\farm_location\AssetLocationInterface;
use Drupal\farm_location\LogLocationInterface;
use Drupal\farm_log\LogQueryFactoryInterface;
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
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   The group membership service.
   */
  public function __construct(LogLocationInterface $log_location, LogQueryFactoryInterface $log_query_factory, EntityTypeManagerInterface $entity_type_manager, TimeInterface $time, GroupMembershipInterface $group_membership) {
    parent::__construct($log_location, $log_query_factory, $entity_type_manager, $time);
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
      $container->get('group.membership')
    );
  }

}
