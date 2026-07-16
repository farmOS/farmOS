<?php

declare(strict_types=1);

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
  public function normalize($field_item, $format = NULL, array $context = []): array {
    /** @var \Drupal\text\Plugin\Field\FieldType\TextLongItem $field_item */

    // If processed_text is explicitly set, return processed or raw user input
    // accordingly.
    if (isset($context['processed_text'])) {
      if ($context['processed_text']) {
        /** @var \Drupal\filter\Render\FilteredMarkup $processed_text */
        $processed_text = $field_item->get('processed')->getValue();
        return ['value' => (string) $processed_text];
      }
      else {
        return ['value' => $field_item->get('value')->getValue()];
      }
    }

    // Delegate to the parent method.
    return parent::normalize($field_item, $format, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, ?string $format = NULL, array $context = []): bool {
    return $data instanceof TextLongItem && $format == static::FORMAT;
  }

}
