<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

/**
 * Public Piknik place page: {base}/place/{id} (not /dashboard/place/…).
 *
 * Only BUSINESS / ORGANIZATION places are linked. CONSUMER profiles are not.
 */
final class PlaceWebUrl {

  private const PUBLIC_TYPES = ['BUSINESS', 'ORGANIZATION'];

  private const PUBLIC_ROLES = [
    'FARM',
    'FARM_CSA',
    'COMMUNITY_GARDEN',
    'COMMUNITY_KITCHEN',
    'MERCHANT',
    'RESTAURANT',
    'DELIVERY',
    'PROCESSOR',
    'MARKET',
    'FOOD_BANK',
    'REGION',
  ];

  public static function forPlace(string $base, string $place_id, string $participant_type = '', string $primary_role = ''): string {
    if (!self::isPublicPlace($participant_type, $primary_role)) {
      return '';
    }
    $base = rtrim($base, '/');
    if ($base === '' || !preg_match('#^https?://#i', $base)) {
      return '';
    }
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $place_id)) {
      return '';
    }
    return $base . '/place/' . rawurlencode($place_id);
  }

  public static function isPublicPlace(string $participant_type, string $primary_role): bool {
    $type = strtoupper(trim($participant_type));
    $role = strtoupper(trim($primary_role));
    if ($type === 'CONSUMER' || $role === 'CONSUMER') {
      return FALSE;
    }
    if (in_array($type, self::PUBLIC_TYPES, TRUE)) {
      return TRUE;
    }
    // Fallback when type is missing from the payload.
    return $type === '' && in_array($role, self::PUBLIC_ROLES, TRUE);
  }

}
