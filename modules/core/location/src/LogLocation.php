<?php

namespace Drupal\farm_location;

use Drupal\log\Entity\LogInterface;

/**
 * Log location logic.
 */
class LogLocation implements LogLocationInterface {

  /**
   * The name of the log location field.
   *
   * @var string
   */
  const LOG_FIELD_LOCATION = 'location';

  /**
   * The name of the log geometry field.
   *
   * @var string
   */
  const LOG_FIELD_GEOMETRY = 'geometry';

  /**
   * {@inheritdoc}
   */
  public function hasLocation(LogInterface $log): bool {
    return !$log->get(static::LOG_FIELD_LOCATION)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function hasGeometry(LogInterface $log): bool {
    return !$log->get(static::LOG_FIELD_GEOMETRY)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation(LogInterface $log): array {
    return $log->{static::LOG_FIELD_LOCATION}->referencedEntities() ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function getGeometry(LogInterface $log): string {
    return $log->get(static::LOG_FIELD_GEOMETRY)->value ?? '';
  }

  /**
   * {@inheritdoc}
   */
  public function setLocation(LogInterface $log, array $assets): void {
    foreach ($assets as $asset) {
      $log->{static::LOG_FIELD_LOCATION}[] = ['target_id' => $asset->id()];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setGeometry(LogInterface $log, string $wkt): void {
    $log->{static::LOG_FIELD_GEOMETRY}->value = $wkt;
  }

}
