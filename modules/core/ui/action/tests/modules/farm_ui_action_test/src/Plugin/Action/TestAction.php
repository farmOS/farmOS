<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action_test\Plugin\Action;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Action\Attribute\Action;
use Drupal\Core\Action\Plugin\Action\EntityActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Simple action plugin for testing.
 */
#[Action(
  id: 'test_action',
  label: new TranslatableMarkup('Test action'),
  type: 'asset',
)]
class TestAction extends EntityActionBase {

  /**
   * {@inheritdoc}
   */
  public function access($object, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = AccessResult::allowedIf(empty($object->get('archived')->value));
    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function execute($object = NULL) {
    $object->set('archived', TRUE);
    $object->save();
  }

}
