<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_oauth_client\Connection;

/**
 * Connection group on the Piknik settings form.
 */
final class SettingsLayout {

  use StringTranslationTrait;

  public function connection(bool $connected, Connection $connection): array {
    $section = [
      '#type' => 'container',
      '#weight' => 20,
      '#attributes' => ['class' => ['farm-piknik-connection']],
      'heading' => ['#weight' => -10] + $this->heading($this->t('Connection')),
      'connect' => $this->connect($connected),
      'note' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('After connecting, choose the farm place used by this site. Harvest posting and listings are on the Piknik page.'),
        '#weight' => -5,
      ],
    ];
    return $section + ConnectionFields::elements($connection);
  }

  /**
   * @return array<string, mixed>
   */
  private function heading(\Stringable $label): array {
    return [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $label,
      '#attributes' => ['class' => ['farm-piknik-section']],
    ];
  }

  /**
   * @return array<string, mixed>
   */
  private function connect(bool $connected): array {
    $button = $connected
      ? [
        '#type' => 'submit',
        '#value' => $this->t('Disconnect from Piknik'),
        '#name' => 'disconnect',
        '#submit' => ['::disconnect'],
        '#limit_validation_errors' => [],
      ]
      : [
        '#type' => 'submit',
        '#value' => $this->t('Connect to Piknik'),
        '#name' => 'signin',
        '#submit' => ['::signIn'],
        '#attributes' => ['class' => ['button', 'button--primary']],
      ];
    $actions = [
      '#type' => 'container',
      '#weight' => 0,
      '#attributes' => ['class' => ['farm-piknik-connect']],
    ];
    $actions[$connected ? 'disconnect' : 'signin'] = $button;
    return $actions;
  }

}
