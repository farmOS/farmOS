<?php

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
  public function normalize($field_item, $format = NULL, array $context = []): array|string|int|float|bool|\ArrayObject|NULL {

    // Attempt to load the referenced entity.
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    if ($entity = $field_item->get('entity')->getValue()) {

      // Return content entity labels, if desired.
      if ($entity instanceof ContentEntityInterface && isset($context['content_entity_labels']) && $context['content_entity_labels'] === TRUE) {
        return $entity->label();
      }

      // Return config entity IDs, if desired.
      if ($entity instanceof ConfigEntityInterface && isset($context['config_entity_ids']) && $context['config_entity_ids'] === TRUE) {
        return $entity->id();
      }
    }

    // Otherwise, delegate to the parent method.
    return parent::normalize($field_item, $format, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, string $format = NULL, array $context = []): bool {
    return $data instanceof EntityReferenceItemInterface && $format == static::FORMAT;
  }

}
