<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\migrate\process\SkipOnEmpty;
use Drupal\migrate\Row;

/**
 * Skips processing (and mapping) the current row when the input value is empty.
 *
 * This extends from the core skip_on_empty plugin, and sets the $save_to_map
 * parameter of MigrateSkipRowException() to FALSE to prevent the row from
 * being saved to the {migrate_map_*} table entirely.
 *
 * @see \Drupal\migrate\Plugin\migrate\process\SkipOnEmpty
 *
 * @MigrateProcessPlugin(
 *   id = "skip_map_on_empty"
 * )
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class SkipMapOnEmpty extends SkipOnEmpty {

  /**
   * {@inheritdoc}
   */
  public function row($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$value) {
      $message = !empty($this->configuration['message']) ? $this->configuration['message'] : '';
      throw new MigrateSkipRowException($message, FALSE);
    }
    return $value;
  }

}
