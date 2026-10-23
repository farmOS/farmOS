<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\State\StateInterface;

/**
 * Board browse distance for GET /api/listings maxRadius (kilometers).
 */
final class BrowseRadius {

  public const KEY = 'farm_piknik.browse_radius_km';

  public const DEFAULT = 50;

  /** @var list<int> */
  public const CHOICES = [10, 25, 50, 100];

  public static function get(StateInterface $state): int {
    return self::clamp((int) $state->get(self::KEY, self::DEFAULT));
  }

  public static function set(StateInterface $state, int $km): void {
    $state->set(self::KEY, self::clamp($km));
  }

  public static function clamp(int $km): int {
    return in_array($km, self::CHOICES, TRUE) ? $km : self::DEFAULT;
  }

  /**
   * @return array<int, string>
   */
  public static function options(): array {
    $out = [];
    foreach (self::CHOICES as $km) {
      $out[$km] = (string) t('@km km', ['@km' => $km]);
    }
    return $out;
  }

}
