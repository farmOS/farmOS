<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Entity\EntityInterface;

/**
 * Builds a Piknik listing from a harvest, farm-goods name, or trigger.
 */
final class HarvestPayload {

  public const SELL = 'for_sale';

  public const WASTE = 'surplus_available';

  /**
   * @param array{id: string, crops: list<string>, listing_type: string, template: string, mode: string} $trigger
   *
   * @return array<string, mixed>|null
   */
  public static function fromTrigger(string $farm_goods, string $participant_id, array $trigger): ?array {
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $participant_id)) {
      return NULL;
    }
    $farm_goods = trim($farm_goods);
    if (!preg_match("/^[\\p{L}\\p{N}][\\p{L}\\p{N} '.-]{0,79}$/u", $farm_goods)) {
      return NULL;
    }
    $type = $trigger['listing_type'] ?? self::SELL;
    if (!isset(TriggerCatalog::TYPES[$type])) {
      return NULL;
    }
    $template = is_string($trigger['template'] ?? NULL) && $trigger['template'] !== ''
      ? $trigger['template']
      : 'Fresh ' . $farm_goods . ' for sale';
    return [
      'participantId' => $participant_id,
      'listingType' => $type,
      'title' => $farm_goods,
      'description' => TriggerBootstrap::resolveTemplate($template, $farm_goods),
      'contactMethod' => 'email',
      'shareContactEmail' => FALSE,
      'endDate' => gmdate('c', \Drupal::time()->getRequestTime() + 604800),
    ];
  }

  /**
   * Ad hoc listing. Optional fields may be empty strings.
   *
   * @param array{participantId: string, listingType: string, title: string, description?: string, quantity?: string, wantedInReturn?: string, endDate?: string} $input
   *
   * @return array<string, mixed>|null
   */
  public static function adHoc(array $input): ?array {
    $place = $input['participantId'] ?? '';
    $type = $input['listingType'] ?? '';
    $title = trim((string) ($input['title'] ?? ''));
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $place) || !isset(TriggerCatalog::TYPES[$type])) {
      return NULL;
    }
    if (!preg_match("/^[\\p{L}\\p{N}][\\p{L}\\p{N} '.-]{0,79}$/u", $title)) {
      return NULL;
    }
    $payload = [
      'participantId' => $place,
      'listingType' => $type,
      'title' => $title,
      'description' => trim((string) ($input['description'] ?? '')) ?: $title,
      'contactMethod' => 'email',
      'shareContactEmail' => FALSE,
      'endDate' => !empty($input['endDate']) ? (string) $input['endDate'] : gmdate('c', \Drupal::time()->getRequestTime() + 604800),
    ];
    $quantity = trim((string) ($input['quantity'] ?? ''));
    if ($quantity !== '') {
      $payload['quantity'] = mb_substr($quantity, 0, 40);
    }
    $wanted = trim((string) ($input['wantedInReturn'] ?? ''));
    if ($wanted !== '' && $type === 'wanted_to_buy') {
      $payload['wantedInReturn'] = mb_substr($wanted, 0, 120);
    }
    return $payload;
  }

  /**
   * Canonical farm-goods name from a linked plant_type term, or NULL.
   */
  public static function referencedFarmGoodsName(EntityInterface $log): ?string {
    if (!$log->hasField('asset')) {
      return NULL;
    }
    foreach ($log->get('asset')->referencedEntities() as $asset) {
      if (!$asset->hasField('plant_type') || $asset->get('plant_type')->isEmpty()) {
        continue;
      }
      $term = $asset->get('plant_type')->entity;
      if ($term) {
        $label = trim((string) $term->label());
        return $label !== '' ? $label : NULL;
      }
    }
    return NULL;
  }

  /**
   * Farm-goods name for listing copy (plant type, asset label, or log title).
   */
  public static function farmGoodsName(EntityInterface $log): string {
    return FarmGoods::nameFromHarvest($log) ?? '';
  }

}
