<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Table of normalized Piknik listings.
 */
final class ListingTable {

  use StringTranslationTrait;

  /**
   * @param list<array{id: string, title: string, emoji?: string, listing_url?: string, type: string, status: string, place: string, place_url: string, ends: string, distance?: string}> $rows
   * @param array{place?: bool, status?: bool, status_toggle?: bool, distance?: bool} $options
   *
   * @return array<string, mixed>
   */
  public function build(array $rows, string $empty, array $options = []): array {
    $show_place = $options['place'] ?? TRUE;
    $show_status = $options['status'] ?? TRUE;
    $show_distance = $options['distance'] ?? FALSE;
    $toggle = $options['status_toggle'] ?? FALSE;
    if ($rows === []) {
      return [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $empty,
        '#attributes' => ['class' => ['farm-piknik-board__empty']],
      ];
    }
    $badge = new StatusBadge();
    $header = [$this->t('Title'), $this->t('Type')];
    if ($show_distance) {
      $header[] = $this->t('Distance');
    }
    if ($show_status) {
      $header[] = $this->t('Status');
    }
    if ($show_place) {
      $header[] = $this->t('Place');
    }
    $header[] = $this->t('Ends');
    $table_rows = [];
    foreach ($rows as $row) {
      $cells = [
        $this->titleCell($row['title'], $row['listing_url'] ?? '', $row['emoji'] ?? ''),
        $row['type'],
      ];
      if ($show_distance) {
        $cells[] = $row['distance'] ?? '—';
      }
      if ($show_status) {
        $cells[] = $badge->cell($row['status'], $row['id'] ?? '', $toggle);
      }
      if ($show_place) {
        $cells[] = $this->placeCell($row['place'], $row['place_url'] ?? '');
      }
      $cells[] = $row['ends'];
      $table_rows[] = $cells;
    }
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $table_rows,
      '#attributes' => ['class' => ['farm-piknik-board__table']],
    ];
  }

  /**
   * @return array<string, mixed>|string
   */
  private function titleCell(string $title, string $url, string $emoji = ''): array|string {
    $label = $title !== '' ? $title : '—';
    if ($emoji !== '' && $label !== '—') {
      $label = $emoji . ' ' . $label;
    }
    if ($url === '' || $title === '') {
      return $label;
    }
    return [
      'data' => [
        '#type' => 'link',
        '#title' => $label,
        '#url' => Url::fromUri($url),
        '#attributes' => [
          'target' => '_blank',
          'rel' => 'noopener noreferrer',
          'class' => ['farm-piknik-listing-link'],
        ],
      ],
    ];
  }

  /**
   * @return array<string, mixed>|string
   */
  private function placeCell(string $name, string $url): array|string {
    if ($name === '' || $name === '—' || $url === '') {
      return $name !== '' ? $name : '—';
    }
    return [
      'data' => [
        '#type' => 'link',
        '#title' => $name,
        '#url' => Url::fromUri($url),
        '#attributes' => [
          'target' => '_blank',
          'rel' => 'noopener noreferrer',
          'class' => ['farm-piknik-place-link'],
        ],
      ],
    ];
  }

}
