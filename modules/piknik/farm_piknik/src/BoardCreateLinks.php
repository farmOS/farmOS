<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Primary board actions: list from a harvest, or create a listing directly.
 */
final class BoardCreateLinks {

  use StringTranslationTrait;

  /**
   * @return array<string, mixed>
   */
  public function build(): array {
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-board__primary']],
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Post to the marketplace'),
        '#attributes' => ['class' => ['farm-piknik-board__primary-title']],
      ],
      'help' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('Create a listing from a completed harvest, or enter listing details yourself.'),
        '#attributes' => ['class' => ['farm-piknik-board__primary-help']],
      ],
      'actions' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['farm-piknik-board__create-actions']],
        'from_harvest' => $this->link(
          $this->t('List from a harvest'),
          'farm_piknik.create_from_crop',
          TRUE,
        ),
        'adhoc' => $this->link(
          $this->t('New listing'),
          'farm_piknik.create_adhoc',
          FALSE,
        ),
      ],
    ];
  }

  /**
   * @return array<string, mixed>
   */
  private function link(\Stringable|string $title, string $route, bool $primary): array {
    $classes = ['button'];
    if ($primary) {
      $classes[] = 'button--primary';
    }
    return [
      '#type' => 'link',
      '#title' => $title,
      '#url' => Url::fromRoute($route),
      '#attributes' => ['class' => $classes],
    ];
  }

}
