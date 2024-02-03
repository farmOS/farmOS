<?php

namespace Drupal\farm_csv\Normalizer;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\serialization\Normalizer\ContentEntityNormalizer as CoreContentEntityNormalizer;

/**
 * Normalizes farmOS content entities for CSV exports.
 */
class ContentEntityNormalizer extends CoreContentEntityNormalizer {

  /**
   * The supported format.
   */
  const FORMAT = 'csv';

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []): array|string|int|float|bool|\ArrayObject|NULL {
    $data = parent::normalize($entity, $format, $context);

    // If columns were explicitly included, remove others.
    if (!empty($context['include_columns'])) {
      foreach (array_keys($data) as $key) {
        if (!in_array($key, $context['include_columns'])) {
          unset($data[$key]);
        }
      }
    }

    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, string $format = NULL, array $context = []): bool {
    return $data instanceof ContentEntityInterface && $format == static::FORMAT;
  }

}
