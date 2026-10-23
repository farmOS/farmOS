<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\State\StateInterface;

/**
 * Stored harvest listing triggers (add / edit / remove on the board).
 *
 * @phpstan-type Trigger array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool}
 */
final class TriggerCatalog {

  public const TYPES = [
    HarvestPayload::SELL => 'For sale',
    HarvestPayload::WASTE => 'Surplus',
    'wanted_to_buy' => 'Looking to buy',
  ];

  public const MODES = [
    'auto' => 'when the harvest is finished',
    'manual' => 'immediately',
  ];

  /**
   * @return list<Trigger>
   */
  public static function all(StateInterface $state): array {
    $raw = $state->get('farm_piknik.triggers');
    return is_array($raw) ? self::normalizeList($raw) : TriggerBootstrap::migrate($state);
  }

  /**
   * @param list<Trigger> $triggers
   */
  public static function save(StateInterface $state, array $triggers): void {
    $state->set('farm_piknik.triggers', array_values(self::normalizeList($triggers)));
  }

  /**
   * @return Trigger|null
   */
  public static function one(StateInterface $state, string $id): ?array {
    foreach (self::all($state) as $trigger) {
      if ($trigger['id'] === $id) {
        return $trigger;
      }
    }
    return NULL;
  }

  /**
   * Active automatic triggers that apply to this crop.
   *
   * @return list<Trigger>
   */
  public static function autoForCrop(StateInterface $state, string $crop): array {
    $crop = trim($crop);
    $matches = [];
    foreach (self::all($state) as $trigger) {
      if (!$trigger['active'] || $trigger['mode'] !== 'auto') {
        continue;
      }
      if ($trigger['crops'] === [] || in_array($crop, $trigger['crops'], TRUE)) {
        $matches[] = $trigger;
      }
    }
    return $matches;
  }

  public static function appliesToCrop(array $trigger, string $crop): bool {
    return $trigger['crops'] === [] || in_array(trim($crop), $trigger['crops'], TRUE);
  }

  public static function setActive(StateInterface $state, string $id, bool $active): void {
    $list = self::all($state);
    foreach ($list as &$trigger) {
      if ($trigger['id'] === $id) {
        $trigger['active'] = $active;
      }
    }
    self::save($state, $list);
  }

  public static function deactivateAll(StateInterface $state): void {
    $list = self::all($state);
    foreach ($list as &$trigger) {
      $trigger['active'] = FALSE;
    }
    self::save($state, $list);
  }

  /**
   * @param list<mixed> $list
   *
   * @return list<Trigger>
   */
  private static function normalizeList(array $list): array {
    $out = [];
    foreach ($list as $row) {
      if (is_array($row)) {
        $out[] = self::normalize($row);
      }
    }
    return $out;
  }

  /**
   * @param array<string, mixed> $row
   *
   * @return Trigger
   */
  public static function normalize(array $row): array {
    $type = is_string($row['listing_type'] ?? NULL) ? $row['listing_type'] : HarvestPayload::SELL;
    if (!isset(self::TYPES[$type])) {
      $type = HarvestPayload::SELL;
    }
    $mode = ($row['mode'] ?? '') === 'auto' ? 'auto' : 'manual';
    $template = is_string($row['template'] ?? NULL) ? trim($row['template']) : '';
    if ($template === '') {
      $template = $type === HarvestPayload::WASTE
        ? 'Available as surplus'
        : 'Fresh for sale';
    }
    $crops = [];
    if (is_array($row['crops'] ?? NULL)) {
      foreach ($row['crops'] as $crop) {
        if (is_string($crop) && preg_match("/^[\\p{L}\\p{N}][\\p{L}\\p{N} '.-]{0,79}$/u", trim($crop))) {
          $crops[] = trim($crop);
        }
      }
    }
    $id = is_string($row['id'] ?? NULL) && preg_match('/^[A-Za-z0-9_-]{8,40}$/', $row['id'])
      ? $row['id']
      : self::newId();
    $active = array_key_exists('active', $row) ? (bool) $row['active'] : TRUE;
    return [
      'id' => $id,
      'crops' => array_values(array_unique($crops)),
      'listing_type' => $type,
      'template' => mb_substr($template, 0, 200),
      'mode' => $mode,
      'active' => $active,
    ];
  }

  public static function newId(): string {
    return bin2hex(random_bytes(8));
  }

}
