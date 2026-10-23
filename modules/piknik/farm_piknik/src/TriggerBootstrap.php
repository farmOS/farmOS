<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\State\StateInterface;

/**
 * Seeds / migrates harvest triggers into crop-specific rows (no any-crop).
 *
 * @phpstan-type Trigger array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool}
 */
final class TriggerBootstrap {

  /**
   * First-time seed from legacy opt_in / template state keys.
   *
   * @return list<Trigger>
   */
  public static function migrate(StateInterface $state): array {
    $sell = (string) $state->get('farm_piknik.sell_template', 'Fresh {crop} for sale');
    $waste = (string) $state->get('farm_piknik.waste_template', '{crop} available as surplus');
    $opt = (bool) $state->get('farm_piknik.opt_in');
    $triggers = [
      TriggerCatalog::normalize([
        'id' => TriggerCatalog::newId(),
        'crops' => [],
        'listing_type' => HarvestPayload::SELL,
        'template' => $sell !== '' ? $sell : 'Fresh {crop} for sale',
        'mode' => $opt ? 'auto' : 'manual',
        'active' => TRUE,
      ]),
      TriggerCatalog::normalize([
        'id' => TriggerCatalog::newId(),
        'crops' => [],
        'listing_type' => HarvestPayload::WASTE,
        'template' => $waste !== '' ? $waste : '{crop} available as surplus',
        'mode' => 'manual',
        'active' => TRUE,
      ]),
    ];
    TriggerCatalog::save($state, $triggers);
    return $triggers;
  }

  /**
   * Expand any-crop / {crop} rows into one trigger per real crop name.
   *
   * @param list<string> $crop_names
   */
  public static function expandToCrops(StateInterface $state, array $crop_names): void {
    $crops = [];
    foreach ($crop_names as $name) {
      if (is_string($name) && trim($name) !== '') {
        $crops[] = trim($name);
      }
    }
    $crops = array_values(array_unique($crops));
    if ($crops === []) {
      return;
    }
    $list = TriggerCatalog::all($state);
    $out = [];
    $changed = FALSE;
    foreach ($list as $trigger) {
      if ($trigger['crops'] !== []) {
        $resolved = self::resolveTemplate($trigger['template'], $trigger['crops'][0]);
        if ($resolved !== $trigger['template']) {
          $trigger['template'] = $resolved;
          $changed = TRUE;
        }
        $out[] = $trigger;
        continue;
      }
      $changed = TRUE;
      foreach ($crops as $crop) {
        $out[] = TriggerCatalog::normalize([
          'id' => TriggerCatalog::newId(),
          'crops' => [$crop],
          'listing_type' => $trigger['listing_type'],
          'template' => self::resolveTemplate($trigger['template'], $crop),
          'mode' => $trigger['mode'],
          'active' => $trigger['active'],
        ]);
      }
    }
    if ($changed) {
      TriggerCatalog::save($state, $out);
    }
  }

  public static function resolveTemplate(string $template, string $crop): string {
    $text = trim(str_ireplace('{crop}', $crop, $template));
    return $text !== '' ? $text : 'Fresh ' . $crop . ' for sale';
  }

}
