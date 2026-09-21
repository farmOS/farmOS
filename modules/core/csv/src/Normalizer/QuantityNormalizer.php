<?php

declare(strict_types=1);

namespace Drupal\farm_csv\Normalizer;

use Drupal\quantity\Entity\QuantityInterface;

/**
 * Normalizes quantity entities for CSV exports.
 */
class QuantityNormalizer extends ContentEntityNormalizer {

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []): array {
    $data = parent::normalize($entity, $format, $context);

    // Query the log associated with the quantity.
    /** @var \Drupal\log\Entity\LogInterface[] $logs */
    $logs = $this->entityTypeManager->getStorage('log')->loadByProperties([
      'quantity' => $entity->id(),
    ]);

    // Prepend log data to the quantity data for normalization.
    if ($log = reset($logs)) {
      $data = [
        'log_id' => $log->id(),
        'log_status' => $log->get('status')->value,
        // PHPStan level 2+ throws the following error on the next line:
        // Call to an undefined method
        // Symfony\Component\Serializer\SerializerInterface::normalize().
        // We ignore this because we are following Drupal core's pattern.
        // @phpstan-ignore method.notFound
        'log_timestamp' => $this->serializer->normalize($log->get('timestamp')->first(), $format, $context),
        'log_type' => $log->bundle(),
        'log_name' => $log->getName(),
      ] + $data;
    }
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, ?string $format = NULL, array $context = []): bool {
    return $data instanceof QuantityInterface && $format == static::FORMAT;
  }

}
