<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Card list of farm goods set up to post after harvest.
 */
final class BoardTriggerList {

  use StringTranslationTrait;

  /**
   * @param list<array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool}> $triggers
   *
   * @return array<string, mixed>
   */
  public function build(array $triggers): array {
    if ($triggers === []) {
      return [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No harvest listings configured yet.'),
        '#attributes' => ['class' => ['farm-piknik-triggers__empty']],
      ];
    }
    $items = [];
    foreach ($triggers as $i => $trigger) {
      $items['t' . $i] = $this->card($trigger);
    }
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-triggers__list']],
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => $this->t('Harvest listing rules'),
        '#attributes' => ['class' => ['farm-piknik-triggers__list-title']],
      ],
    ] + $items;
  }

  /**
   * @param array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool} $trigger
   *
   * @return array<string, mixed>
   */
  private function card(array $trigger): array {
    $item = $trigger['crops'][0] ?? (string) $this->t('No farm good');
    $description = $trigger['crops'] !== []
      ? TriggerBootstrap::resolveTemplate($trigger['template'], $trigger['crops'][0])
      : $trigger['template'];
    $type = TriggerCatalog::TYPES[$trigger['listing_type']] ?? $trigger['listing_type'];
    $when = TriggerCatalog::MODES[$trigger['mode']] ?? $trigger['mode'];
    $recurring = ($trigger['mode'] ?? '') === 'auto';
    $classes = ['farm-piknik-trigger-card', 'farm-piknik-trigger-card--active'];
    if ($recurring) {
      $classes[] = 'farm-piknik-trigger-card--recurring';
    }
    $header = [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-trigger-card__header']],
    ];
    if ($recurring) {
      $header['recur'] = [
        '#type' => 'html_tag',
        '#tag' => 'span',
        // Circular arrows: repeats each time this harvest is finished.
        '#value' => '↻',
        '#attributes' => [
          'class' => ['farm-piknik-trigger-card__recur'],
          'title' => (string) $this->t('Repeats each time this harvest is finished'),
          'aria-label' => (string) $this->t('Repeats each time this harvest is finished'),
          'role' => 'img',
        ],
      ];
    }
    $header['meta'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('@item · @type · @when', [
        '@item' => $item,
        '@type' => $type,
        '@when' => $when,
      ]),
      '#attributes' => ['class' => ['farm-piknik-trigger-card__meta']],
    ];
    return [
      '#type' => 'container',
      '#attributes' => ['class' => $classes],
      'header' => $header,
      'template' => $this->fieldRow((string) $this->t('Description'), $description),
      'actions' => $this->actions($trigger),
    ];
  }

  /**
   * @return array<string, mixed>
   */
  private function fieldRow(string $label, string $value): array {
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-trigger-card__row']],
      'label' => [
        '#type' => 'html_tag',
        '#tag' => 'span',
        '#value' => $label,
        '#attributes' => ['class' => ['farm-piknik-trigger-card__label']],
      ],
      'value' => [
        '#type' => 'html_tag',
        '#tag' => 'span',
        '#value' => $value,
        '#attributes' => ['class' => ['farm-piknik-trigger-card__value']],
      ],
    ];
  }

  /**
   * @param array{id: string, mode: string, active: bool} $trigger
   *
   * @return array<string, mixed>
   */
  private function actions(array $trigger): array {
    $id = $trigger['id'];
    $actions = [
      '#type' => 'container',
      '#attributes' => ['class' => ['farm-piknik-trigger-card__actions']],
      'edit' => [
        '#type' => 'submit',
        '#value' => $this->t('Edit'),
        '#name' => 'edit:' . $id,
        '#submit' => ['::submitTriggerAction'],
        '#limit_validation_errors' => [],
        '#attributes' => ['class' => ['button--small']],
      ],
      'remove' => [
        '#type' => 'submit',
        '#value' => $this->t('Delete'),
        '#name' => 'remove:' . $id,
        '#submit' => ['::submitTriggerAction'],
        '#limit_validation_errors' => [],
        '#attributes' => ['class' => ['button--small', 'button--danger']],
      ],
    ];
    if ($trigger['mode'] === 'manual') {
      $actions['run'] = [
        '#type' => 'submit',
        '#value' => $this->t('Post'),
        '#name' => 'run:' . $id,
        '#submit' => ['::submitTriggerAction'],
        '#limit_validation_errors' => [],
        '#attributes' => ['class' => ['button--small', 'button--primary']],
      ];
    }
    return $actions;
  }

}
