<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

/**
 * Piknik wordmark: full intro on settings, compact header on the board.
 */
final class Wordmark {

  /**
   * Logo plus “what / why join” intro — settings only.
   */
  public static function element(): array {
    $urls = self::imageUrls();
    return [
      '#type' => 'inline_template',
      '#weight' => -100,
      '#template' => '<div class="farm-piknik-wordmark"><img class="farm-piknik-wordmark__on-light" src="{{ light }}" alt="Piknik"><img class="farm-piknik-wordmark__on-dark" src="{{ dark }}" alt=""></div><div class="farm-piknik-intro"><p><strong>{{ what_label }}</strong> {{ what }}</p><p><strong>{{ why_label }}</strong></p><ul><li><strong>{{ business_label }}</strong> {{ business }}</li><li><strong>{{ operational_label }}</strong> {{ operational }}</li></ul></div>',
      '#context' => [
        'light' => $urls['light'],
        'dark' => $urls['dark'],
        'what_label' => t('What it is.'),
        'what' => t('Piknik.Spot'),
        'why_label' => t('Why join.'),
        'business_label' => t('Business.'),
        'business' => t('Nearby buyers can find what you have available, and you can post a listing or a request.'),
        'operational_label' => t('Operational.'),
        'operational' => t('When a harvest record is marked done, Piknik can post one offer from that record.'),
      ],
    ];
  }

  /**
   * Logo plus a short day-to-day board blurb — not the settings onboarding copy.
   */
  public static function board(int $radius_km = BrowseRadius::DEFAULT): array {
    $urls = self::imageUrls();
    $km = BrowseRadius::clamp($radius_km);
    return [
      '#type' => 'inline_template',
      '#weight' => -100,
      '#template' => '<div class="farm-piknik-wordmark"><img class="farm-piknik-wordmark__on-light" src="{{ light }}" alt="Piknik"><img class="farm-piknik-wordmark__on-dark" src="{{ dark }}" alt=""></div><p class="farm-piknik-board__summary">{{ summary }}</p>',
      '#context' => [
        'light' => $urls['light'],
        'dark' => $urls['dark'],
        'summary' => t('Manage your marketplace listings and browse nearby offers within @km kilometers.', ['@km' => $km]),
      ],
    ];
  }

  /**
   * @return array{light: string, dark: string}
   */
  private static function imageUrls(): array {
    $path = \Drupal::service('extension.list.module')->getPath('farm_piknik');
    $urls = \Drupal::service('file_url_generator');
    return [
      'light' => $urls->generateString($path . '/images/piknik-wordmark.png'),
      'dark' => $urls->generateString($path . '/images/piknik-wordmark-light.png'),
    ];
  }

}
