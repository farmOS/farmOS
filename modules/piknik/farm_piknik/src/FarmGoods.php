<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Farm-goods names for board search and harvest-trigger dropdowns.
 *
 * Sources: plant/product type taxonomies, animal assets ("breed - name"),
 * recent harvests. Animal breeds are not listed alone (avoids duplicates).
 */
final class FarmGoods {

  public function __construct(
    private EntityTypeManagerInterface $entities,
  ) {}

  /**
   * @return array<string, string>
   *   Options keyed by farm-goods label.
   */
  public function options(): array {
    $names = [];
    // animal_type terms omitted: animals appear once via animalAssetLabels().
    foreach (['plant_type', 'product_type'] as $vid) {
      foreach ($this->vocabularyLabels($vid) as $name) {
        $names[$name] = $name;
      }
    }
    foreach ($this->animalAssetLabels() as $name) {
      $names[$name] = $name;
    }
    foreach ($this->recentHarvestNames() as $name) {
      $names[$name] = $name;
    }
    ksort($names, SORT_NATURAL | SORT_FLAG_CASE);
    return $names;
  }

  /**
   * @return list<string>
   */
  public function names(): array {
    return array_values($this->options());
  }

  /**
   * Display label for an asset: "breed - name" for animals, else name.
   */
  public static function labelForAsset(EntityInterface $asset): string {
    $name = trim((string) $asset->label());
    if ($name === '') {
      return '';
    }
    if ($asset->bundle() !== 'animal' || !$asset->hasField('animal_type')) {
      return $name;
    }
    if ($asset->get('animal_type')->isEmpty() || !$asset->get('animal_type')->entity) {
      return $name;
    }
    $breed = trim((string) $asset->get('animal_type')->entity->label());
    return $breed !== '' ? $breed . ' - ' . $name : $name;
  }

  /**
   * plant_type term, else linked asset label, else harvest log title.
   */
  public static function nameFromHarvest(EntityInterface $log): ?string {
    $from_type = HarvestPayload::referencedFarmGoodsName($log);
    if ($from_type !== NULL && $from_type !== '') {
      return $from_type;
    }
    if ($log->hasField('asset')) {
      foreach ($log->get('asset')->referencedEntities() as $asset) {
        $label = self::labelForAsset($asset);
        if ($label !== '') {
          return $label;
        }
      }
    }
    $title = trim((string) $log->label());
    return $title !== '' ? $title : NULL;
  }

  /**
   * @return list<string>
   */
  private function vocabularyLabels(string $vid): array {
    try {
      $terms = $this->entities->getStorage('taxonomy_term')->loadByProperties([
        'vid' => $vid,
        'status' => 1,
      ]);
    }
    catch (\Throwable) {
      return [];
    }
    $names = [];
    foreach ($terms as $term) {
      $label = trim((string) $term->label());
      if (self::ok($label)) {
        $names[] = $label;
      }
    }
    return $names;
  }

  /**
   * @return list<string>
   */
  private function animalAssetLabels(): array {
    try {
      $ids = $this->entities->getStorage('asset')->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', 'animal')
        ->condition('archived', FALSE)
        ->sort('name', 'ASC')
        ->range(0, 100)
        ->execute();
      $assets = $this->entities->getStorage('asset')->loadMultiple($ids);
    }
    catch (\Throwable) {
      return [];
    }
    $names = [];
    foreach ($assets as $asset) {
      $label = self::labelForAsset($asset);
      if (self::ok($label)) {
        $names[] = $label;
      }
    }
    return $names;
  }

  /**
   * @return list<string>
   */
  private function recentHarvestNames(): array {
    try {
      $ids = $this->entities->getStorage('log')->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', 'harvest')
        ->sort('timestamp', 'DESC')
        ->range(0, 40)
        ->execute();
      $logs = $this->entities->getStorage('log')->loadMultiple($ids);
    }
    catch (\Throwable) {
      return [];
    }
    $names = [];
    foreach ($logs as $log) {
      $name = self::nameFromHarvest($log);
      if ($name !== NULL && self::ok($name)) {
        $names[] = $name;
      }
    }
    return $names;
  }

  private static function ok(string $name): bool {
    return (bool) preg_match("/^[\\p{L}\\p{N}][\\p{L}\\p{N} '.-]{0,79}$/u", $name);
  }

}
