<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Board listings: your posts first; nearby posts behind a details fold.
 */
final class BoardMarketplace {

  use StringTranslationTrait;

  /**
   * @param list<array<string, mixed>> $mine
   * @param list<array<string, mixed>> $matches
   * @param list<array<string, mixed>> $nearby
   *
   * @return array<string, mixed>
   */
  public function build(array $mine, array $matches, array $nearby, int $radius_km = BrowseRadius::DEFAULT): array {
    $table = new ListingTable();
    $radius_km = BrowseRadius::clamp($radius_km);
    $count = count($mine);
    return [
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => ['class' => ['farm-piknik-marketplace', 'farm-piknik-board__listings']],
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Your listings'),
        '#attributes' => ['class' => ['farm-piknik-marketplace__title']],
      ],
      'intro' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $count === 0
          ? $this->t('No listings yet. Create one above. Showing results within @km kilometers.', ['@km' => $radius_km])
          : $this->formatPlural(
            $count,
            'You have 1 listing. Showing results within @km kilometers.',
            'You have @count listings. Showing results within @km kilometers.',
            ['@km' => $radius_km],
          ),
        '#attributes' => ['class' => ['farm-piknik-marketplace__intro']],
      ],
      'radius' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['farm-piknik-board__radius']],
        'browse_radius_km' => [
          '#type' => 'select',
          '#title' => $this->t('Search radius'),
          '#options' => BrowseRadius::options(),
          '#default_value' => $radius_km,
        ],
        'apply' => [
          '#type' => 'submit',
          '#value' => $this->t('Apply'),
          '#name' => 'save_browse_radius',
          '#submit' => ['::submitBrowseRadius'],
          '#limit_validation_errors' => [['marketplace', 'radius', 'browse_radius_km']],
          '#attributes' => [
            'class' => ['button', 'button--small', 'farm-piknik-board__radius-apply'],
          ],
        ],
      ],
      'mine' => $table->build($mine, (string) $this->t('No listings yet.'), [
        'place' => FALSE,
        'status_toggle' => TRUE,
      ]),
      'nearby' => [
        '#type' => 'details',
        '#title' => $this->t('Nearby listings (within @km km)', ['@km' => $radius_km]),
        '#open' => FALSE,
        '#attributes' => ['class' => ['farm-piknik-board__fold']],
        'matches' => $this->block(
          $this->t('Matching your farm goods'),
          $table->build($matches, (string) $this->t('No matching listings nearby.'), [
            'status' => FALSE,
            'distance' => TRUE,
          ]),
        ),
        'others' => $this->block(
          $this->t('Other nearby listings'),
          $table->build($nearby, (string) $this->t('No other listings nearby.'), [
            'status' => FALSE,
            'distance' => TRUE,
          ]),
        ),
      ],
    ];
  }

  /**
   * @param array<string, mixed> $content
   *
   * @return array<string, mixed>
   */
  private function block(\Stringable|string $title, array $content): array {
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-marketplace__block']],
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => $title,
        '#attributes' => ['class' => ['farm-piknik-subsection']],
      ],
      'body' => $content,
    ];
  }

}
