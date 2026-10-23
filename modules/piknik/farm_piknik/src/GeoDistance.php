<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

/**
 * Great-circle distance helpers (kilometers).
 */
final class GeoDistance {

  /**
   * Haversine distance in kilometers.
   */
  public static function km(float $lat1, float $lng1, float $lat2, float $lng2): float {
    $r = 6371.0;
    $d_lat = deg2rad($lat2 - $lat1);
    $d_lng = deg2rad($lng2 - $lng1);
    $a = sin($d_lat / 2) ** 2
      + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($d_lng / 2) ** 2;
    return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
  }

  /**
   * Farmer-facing label, e.g. "3 km" or "0.4 km".
   */
  public static function label(float $km): string {
    if ($km < 1) {
      return number_format(max($km, 0.1), 1) . ' km';
    }
    if ($km < 10) {
      return number_format($km, 1) . ' km';
    }
    return (string) (int) round($km) . ' km';
  }

}
