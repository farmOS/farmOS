<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action_test\Plugin\Action;

use Drupal\Core\Action\Attribute\Action;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Action plugin with a confirmation form for testing.
 */
#[Action(
  id: 'test_action_confirm',
  label: new TranslatableMarkup('Test action with confirmation form'),
  confirm_form_route_name: 'farm_ui_action_test.confirm_form',
  type: 'asset',
)]
class TestActionConfirm extends TestAction {

}
