<?php

declare(strict_types=1);

namespace Drupal\farm_csv\Normalizer;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItemInterface;
use Drupal\serialization\Normalizer\EntityReferenceFieldItemNormalizer as CoreEntityReferenceFieldItemNormalizer;

/**
 * Normalizes entity reference fields for farmOS CSV exports.
 */
class EntityReferenceFieldItemNormalizer extends CoreEntityReferenceFieldItemNormalizer {

  /**
   * The supported format.
   */
  const FORMAT = 'csv';

  /**
   * {@inheritdoc}
   */
  public function normalize($field_item, $format = NULL, array $context = []): array {

    // Attempt to load the referenced entity.
    if ($entity = $field_item->get('entity')->getValue()) {

      // Return content entity labels, if desired.
      if ($entity instanceof ContentEntityInterface && isset($context['content_entity_labels']) && $context['content_entity_labels'] === TRUE) {
        return ['value' => $entity->label()];
      }

      // Return config entity IDs, if desired.
      if ($entity instanceof ConfigEntityInterface && isset($context['config_entity_ids']) && $context['config_entity_ids'] === TRUE) {
        return ['value' => $entity->id()];
      }
    }

    // Otherwise, delegate to the parent method.
    return parent::normalize($field_item, $format, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, ?string $format = NULL, array $context = []): bool {
    return $data instanceof EntityReferenceItemInterface && $format == static::FORMAT;
  }

}
