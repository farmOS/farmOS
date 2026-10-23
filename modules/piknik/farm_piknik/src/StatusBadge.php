<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Active / Inactive badge for listings and harvest triggers.
 */
final class StatusBadge {

  use StringTranslationTrait;

  /**
   * Listing status cell (optional link toggle to a route).
   *
   * @return array<string, mixed>|string
   */
  public function cell(string $status, string $listing_id, bool $toggle): array|string {
    if ($status === '' || $status === '—') {
      return '—';
    }
    $active = $status === 'active';
    $inactive = in_array($status, ['cancelled', 'paused', 'expired', 'fulfilled'], TRUE);
    if (!$active && !$inactive) {
      return $this->span($status, 'other');
    }
    if (!$toggle || $listing_id === '') {
      return $this->span($active ? (string) $this->t('Active') : (string) $this->t('Inactive'), $active ? 'active' : 'inactive');
    }
    return $this->link(
      $active,
      Url::fromRoute('farm_piknik.listing_toggle', ['listing_id' => $listing_id]),
    );
  }

  /**
   * Form submit styled as an Active/Inactive toggle badge.
   *
   * @return array<string, mixed>
   */
  public function toggleSubmit(bool $active, string $name): array {
    return [
      'data' => [
        '#type' => 'submit',
        '#value' => $active ? $this->t('Active') : $this->t('Inactive'),
        '#name' => $name,
        '#submit' => ['::submitTriggerAction'],
        '#limit_validation_errors' => [],
        '#attributes' => [
          'class' => [
            'farm-piknik-status-badge',
            'farm-piknik-status-badge--' . ($active ? 'active' : 'inactive'),
            'farm-piknik-status-badge--toggle',
          ],
          'title' => $active ? $this->t('Set inactive') : $this->t('Set active'),
        ],
      ],
    ];
  }

  /**
   * @return array<string, mixed>
   */
  private function link(bool $active, Url $url): array {
    return [
      'data' => [
        '#type' => 'link',
        '#title' => $active ? $this->t('Active') : $this->t('Inactive'),
        '#url' => $url,
        '#attributes' => [
          'class' => [
            'farm-piknik-status-badge',
            'farm-piknik-status-badge--' . ($active ? 'active' : 'inactive'),
            'farm-piknik-status-badge--toggle',
          ],
          'title' => $active ? $this->t('Set inactive') : $this->t('Set active'),
        ],
      ],
    ];
  }

  /**
   * @return array<string, mixed>
   */
  private function span(string $label, string $mod): array {
    return [
      'data' => [
        '#type' => 'html_tag',
        '#tag' => 'span',
        '#value' => $label,
        '#attributes' => ['class' => ['farm-piknik-status-badge', 'farm-piknik-status-badge--' . $mod]],
      ],
    ];
  }

}
