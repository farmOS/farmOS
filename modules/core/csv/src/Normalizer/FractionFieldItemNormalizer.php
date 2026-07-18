<?php

declare(strict_types=1);

namespace Drupal\farm_csv\Normalizer;

use Drupal\fraction\Plugin\Field\FieldType\FractionItem;
use Drupal\serialization\Normalizer\FieldItemNormalizer;

/**
 * Normalizes fraction fields for farmOS CSV exports.
 */
class FractionFieldItemNormalizer extends FieldItemNormalizer {

  /**
   * The supported format.
   */
  const FORMAT = 'csv';

  /**
   * {@inheritdoc}
   */
  public function normalize($field_item, $format = NULL, array $context = []): array {
    /** @var \Drupal\fraction\Plugin\Field\FieldType\FractionItem $field_item */
    return ['value' => $field_item->get('decimal')->getValue()];
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, ?string $format = NULL, array $context = []): bool {
    return $data instanceof FractionItem && $format == static::FORMAT;
  }

}
