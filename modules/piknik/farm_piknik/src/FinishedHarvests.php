<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Finished (done) harvest logs for “post from a harvest” picking.
 */
final class FinishedHarvests {

  public function __construct(
    private EntityTypeManagerInterface $entities,
  ) {}

  /**
   * Options keyed by log ID: "Farm good — date".
   *
   * @return array<string, string>
   */
  public function options(): array {
    $out = [];
    foreach ($this->logs() as $log) {
      $item = HarvestPayload::farmGoodsName($log);
      if ($item === '') {
        continue;
      }
      $date = $this->dateLabel($log);
      $out[(string) $log->id()] = $date !== ''
        ? $item . ' — ' . $date
        : $item;
    }
    return $out;
  }

  /**
   * @return array{crop: string, quantity: string, label: string}|null
   */
  public function details(string|int $log_id): ?array {
    $log = $this->entities->getStorage('log')->load($log_id);
    if (!$log instanceof LogInterface || $log->bundle() !== 'harvest') {
      return NULL;
    }
    if ($log->get('status')->value !== 'done') {
      return NULL;
    }
    $item = HarvestPayload::farmGoodsName($log);
    if ($item === '') {
      return NULL;
    }
    return [
      'crop' => $item,
      'quantity' => $this->quantityLabel($log),
      'label' => (string) $log->label(),
    ];
  }

  /**
   * @return list<LogInterface>
   */
  private function logs(): array {
    try {
      $ids = $this->entities->getStorage('log')->getQuery()
        ->accessCheck(TRUE)
        ->condition('type', 'harvest')
        ->condition('status', 'done')
        ->sort('timestamp', 'DESC')
        ->range(0, 40)
        ->execute();
      /** @var list<LogInterface> $logs */
      $logs = array_values($this->entities->getStorage('log')->loadMultiple($ids));
      return $logs;
    }
    catch (\Throwable) {
      return [];
    }
  }

  private function dateLabel(LogInterface $log): string {
    $ts = (int) $log->get('timestamp')->value;
    return $ts > 0 ? \Drupal::service('date.formatter')->format($ts, 'custom', 'M j, Y') : '';
  }

  private function quantityLabel(LogInterface $log): string {
    if (!$log->hasField('quantity') || $log->get('quantity')->isEmpty()) {
      return '';
    }
    $parts = [];
    foreach ($log->get('quantity')->referencedEntities() as $qty) {
      $value = $qty->hasField('value') ? trim((string) $qty->get('value')->value) : '';
      $unit = '';
      if ($qty->hasField('units') && !$qty->get('units')->isEmpty() && $qty->get('units')->entity) {
        $unit = trim((string) $qty->get('units')->entity->label());
      }
      $line = trim($value . ($unit !== '' ? ' ' . $unit : ''));
      if ($line !== '') {
        $parts[] = $line;
      }
    }
    return mb_substr(implode(', ', $parts), 0, 40);
  }

}
