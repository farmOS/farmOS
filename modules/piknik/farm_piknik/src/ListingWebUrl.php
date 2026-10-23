<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

/**
 * Public marketplace listing deep-link: {base}/marketplace?listing={id}.
 */
final class ListingWebUrl {

  public static function forListing(string $base, string $listing_id): string {
    $base = rtrim($base, '/');
    if ($base === '' || !preg_match('#^https?://#i', $base)) {
      return '';
    }
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $listing_id)) {
      return '';
    }
    return $base . '/marketplace?listing=' . rawurlencode($listing_id);
  }

}
