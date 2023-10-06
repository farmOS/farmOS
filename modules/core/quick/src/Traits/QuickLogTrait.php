<?php

namespace Drupal\farm_quick\Traits;

use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\log\Entity\Log;

/**
 * Provides methods for working with logs.
 */
trait QuickLogTrait {

  use MessengerTrait;
  use StringTranslationTrait;
  use QuickQuantityTrait;
  use QuickStringTrait;

  /**
   * Returns the quick form ID.
   *
   * This must be implemented by the quick form class that uses this trait.
   *
   * @see \Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface
   *
   * @return string
   *   The quick form ID.
   */
  abstract public function getQuickId();

  /**
   * Create a log.
   *
   * @param array $values
   *   An array of values to initialize the log with.
   *
   * @return \Drupal\log\Entity\LogInterface
   *   The log entity that was created.
   */
  protected function createLog(array $values = []) {

    // Trim the log name to 255 characters.
    if (!empty($values['name'])) {
      $values['name'] = $this->trimString($values['name'], 255);
    }

    // Start a new log entity with the provided values.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create($values);

    // If quantity measurements are provided, reference them from the log.
    if (!empty($values['quantity'])) {
      foreach ($values['quantity'] as $qty) {

        // If the quantity is an array of values, pass it to createQuantity.
        if (is_array($qty)) {
          $log->quantity[] = $this->createQuantity($qty, $log->bundle());
        }

        // Otherwise, add it directly to the log.
        else {
          $log->quantity[] = $qty;
        }
      }
    }

    // If not specified, set the log's status to "done".
    if (!isset($values['status'])) {
      $log->status = 'done';
    }

    // Track which quick form created the entity.
    $log->quick[] = $this->getQuickId();

    // Save the log.
    $log->save();

    // Display a message with a link to the log.
    $message = $this->t('Log created: <a href=":url">@name</a>', [':url' => $log->toUrl()->toString(), '@name' => $log->label()]);
    $this->messenger->addStatus($message);

    // Return the log entity.
    return $log;
  }

}
