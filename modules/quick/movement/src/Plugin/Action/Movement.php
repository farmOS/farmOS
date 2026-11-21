<?php

declare(strict_types=1);

namespace Drupal\farm_quick_movement\Plugin\Action;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Action\Attribute\Action;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\farm_location\AssetLocationInterface;
use Drupal\farm_quick\Plugin\Action\QuickFormActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Action for recording movements.
 */
#[Action(
  id: 'quick_movement',
  label: new TranslatableMarkup('Record movement'),
  confirm_form_route_name: 'farm.quick.movement',
  type: 'asset',
)]
class Movement extends QuickFormActionBase {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    PrivateTempStoreFactory $tempStoreFactory,
    AccountInterface $currentUser,
    protected AssetLocationInterface $assetLocation,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $tempStoreFactory, $currentUser);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // @todo Use autowiring and remove this when the parent class does.
    // @see https://www.drupal.org/project/drupal/issues/3552110
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('tempstore.private'),
      $container->get('current_user'),
      $container->get('asset.location'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuickFormId(): string {
    return 'movement';
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\asset\Entity\AssetInterface $object */
    $result = parent::access($object, $account, TRUE);

    // Deny access if the entity is fixed (not movable).
    $result = $result->orIf(AccessResult::forbiddenIf($this->assetLocation->isFixed($object)));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
