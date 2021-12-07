<?php

namespace Drupal\farm_location_test\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the test activity log type.
 *
 * @LogType(
 *   id = "activity",
 *   label = @Translation("Activity"),
 * )
 */
class Activity extends FarmLogType {

}
