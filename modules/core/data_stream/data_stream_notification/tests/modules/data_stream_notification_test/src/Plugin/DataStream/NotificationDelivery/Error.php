<?php

namespace Drupal\data_stream_notification_test\Plugin\DataStream\NotificationDelivery;

use Drupal\data_stream_notification\Plugin\DataStream\NotificationDelivery\NotificationDeliveryBase;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Delivery plugin for testing that raises an error.
 *
 * @NotificationDelivery(
 *   id = "error",
 *   label = @Translation("Error"),
 *   context_definitions = {
 *     "value" = @ContextDefinition("float", label = @Translation("value"))
 *   }
 * )
 */
class Error extends NotificationDeliveryBase {

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $value = $this->getContextValue('value');
    throw new HttpException(299, "Data stream value triggered a notification exception: $value");
  }

}
