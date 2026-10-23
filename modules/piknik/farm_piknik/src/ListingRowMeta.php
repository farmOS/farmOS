<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

/**
 * Small helpers for normalizing listing table rows.
 */
final class ListingRowMeta {

  /**
   * Prefer poster-picked customEmoji, else categoryEmoji from the browse payload.
   */
  public static function emoji(array $listing): string {
    foreach (['customEmoji', 'categoryEmoji'] as $key) {
      $value = $listing[$key] ?? NULL;
      if (!is_string($value)) {
        continue;
      }
      $emoji = trim($value);
      if ($emoji !== '' && mb_strlen($emoji) <= 8) {
        return $emoji;
      }
    }
    return '';
  }

  public static function titleMatches(string $title, string $crop): bool {
    return $crop !== '' && mb_stripos($title, $crop) !== FALSE;
  }

  public static function typeLabel(string $type): string {
    return match ($type) {
      'for_sale' => (string) t('Selling'),
      'surplus_available' => (string) t('Surplus'),
      'wanted_to_buy' => (string) t('Request'),
      default => $type !== '' ? $type : (string) t('Listing'),
    };
  }

}
