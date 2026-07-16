<?php

declare(strict_types=1);

namespace Drupal\farm_csv\Normalizer;

use Drupal\serialization\Normalizer\TimestampItemNormalizer as CoreTimestampItemNormalizer;

/**
 * Normalizes timestamp fields for farmOS CSV exports.
 */
class TimestampItemNormalizer extends CoreTimestampItemNormalizer {

  /**
   * The supported format.
   */
  const FORMAT = 'csv';

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []): array {
    $data = parent::normalize($object, $format, $context);

    // Return the RFC3339 formatted date, if desired.
    if (isset($context['rfc3339_dates']) && $context['rfc3339_dates'] === TRUE) {
      return ['value' => $data['value']];
    }

    return $data;
  }

}
