<?php

namespace Drupal\farm_entity_test\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the test_override log type.
 *
 * @LogType(
 *   id = "test_override",
 *   label = @Translation("Test Override"),
 * )
 */
class TestOverride extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // We are inheriting from FarmLogType, which adds default bundle fields to
    // all log types. We are going to return an empty array to show that we can
    // disable those default fields on specific log types.
    return [];
  }

}
