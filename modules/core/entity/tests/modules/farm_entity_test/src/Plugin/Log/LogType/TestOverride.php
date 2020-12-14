<?php

namespace Drupal\farm_entity_test\Plugin\Log\LogType;

/**
 * Provides the test_override log type.
 *
 * @LogType(
 *   id = "test_override",
 *   label = @Translation("Test Override"),
 * )
 */
class TestOverride extends Test {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // We are inheriting from the Test log type, which adds a bundle field. We
    // are going to return an empty array to show that we can disable those
    // default fields on specific log types.
    return [];
  }

}
