<?php

declare(strict_types=1);

namespace Drupal\farm_log\Plugin\Action;

use Drupal\Core\Action\Attribute\Action;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\log\Plugin\Action\LogStateChangeBase;

/**
 * Action that marks a log as abandoned.
 */
#[Action(
  id: 'log_mark_as_abandoned_action',
  label: new TranslatableMarkup('Sets a Log as abandoned'),
  type: 'log',
)]

class LogMarkAsAbandoned extends LogStateChangeBase {

  /**
   * {@inheritdoc}
   */
  protected $targetState = 'abandoned';

}
