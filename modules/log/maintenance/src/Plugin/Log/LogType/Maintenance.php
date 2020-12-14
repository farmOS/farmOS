<?php

namespace Drupal\farm_maintenance\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the maintenance log type.
 *
 * @LogType(
 *   id = "maintenance",
 *   label = @Translation("Maintenance"),
 * )
 */
class Maintenance extends FarmLogType {

}
