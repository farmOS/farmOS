<?php

namespace Drupal\farm_csv\Normalizer;

use Drupal\serialization\Normalizer\FieldItemNormalizer;
use Drupal\text\Plugin\Field\FieldType\TextLongItem;

/**
 * Normalizes long text fields for farmOS CSV exports.
 */
class TextLongFieldItemNormalizer extends FieldItemNormalizer {

  /**
   * The supported format.
   */
  const FORMAT = 'csv';

  /**
   * {@inheritdoc}
   */
  public function normalize($field_item, $format = NULL, array $context = []): array|string|int|float|bool|\ArrayObject|NULL {
    /** @var \Drupal\text\Plugin\Field\FieldType\TextLongItem $field_item */

    // Return processed text, if desired.
    if (isset($context['processed_text']) && $context['processed_text'] === TRUE) {
      return $field_item->get('processed')->getValue();
    }

    // Delegate to the parent method.
    return parent::normalize($field_item, $format, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, string $format = NULL, array $context = []): bool {
    return $data instanceof TextLongItem && $format == static::FORMAT;
  }

}
