<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\PublicHttps;
use Drupal\farm_oauth_client\SecretBox;
use Drupal\farm_oauth_client\SiteCallback;

/**
 * Piknik address, client, and callback on the settings form.
 *
 * Live farms use the Piknik address. A test farm, marked with
 * farm_oauth_client_allow_local_http in settings.php, types its own.
 */
final class ConnectionFields {

  public const ADDRESS = 'https://piknik.spot';

  public static function testing(): bool {
    return Settings::get('farm_oauth_client_allow_local_http') === TRUE;
  }

  /**
   * @return array<string, mixed>
   */
  public static function elements(Connection $connection): array {
    $savedCallback = $connection->redirectUri('piknik');
    $address = self::address($connection);
    $fields = [
      'client_id' => [
        '#type' => 'textfield',
        '#title' => t('Client ID'),
        '#default_value' => $connection->clientId('piknik'),
        '#maxlength' => 128,
        '#weight' => 30,
      ],
      'client_secret' => [
        '#type' => 'password',
        '#title' => t('Client secret'),
        '#description' => t('Leave blank to keep the saved secret.'),
        '#weight' => 40,
      ],
      'callback' => [
        '#type' => 'textfield',
        '#title' => t('Callback URL'),
        '#default_value' => $savedCallback !== '' ? $savedCallback : SiteCallback::url('piknik'),
        '#description' => t("Defaults to this farm's address."),
        '#maxlength' => 255,
        '#weight' => 50,
      ],
    ];
    $fields[$address['#type'] === 'url' ? 'base_url' : 'address'] = $address;
    return $fields;
  }

  public static function validate(FormStateInterface $form_state, Connection $connection, SecretBox $secrets): void {
    self::validateCallback($form_state);
    if (self::testing()) {
      try {
        $form_state->setValue('base_url', PublicHttps::pin((string) $form_state->getValue('base_url'))['url']);
      }
      catch (\InvalidArgumentException $e) {
        $form_state->setErrorByName('base_url', $e->getMessage());
      }
    }
    $client_id = trim((string) $form_state->getValue('client_id'));
    if (!preg_match('/^[A-Za-z0-9_-]{1,128}$/', $client_id)) {
      $form_state->setErrorByName('client_id', t('The client id contains characters this form does not accept.'));
    }
    $secret = (string) $form_state->getValue('client_secret');
    if ($secret === '' && !$connection->hasSecret('piknik')) {
      $form_state->setErrorByName('client_secret', t('The client secret is required the first time.'));
    }
    if ($secret !== '' && !$secrets->canSeal()) {
      $form_state->setErrorByName('client_secret', t('farm_oauth_client_key is missing or not 32 bytes.'));
    }
  }

  public static function store(FormStateInterface $form_state, Connection $connection): void {
    $callback = trim((string) $form_state->getValue('callback'));
    $connection->saveRedirect('piknik', $callback === SiteCallback::url('piknik') ? '' : $callback);
    $base = self::testing() ? (string) $form_state->getValue('base_url') : self::ADDRESS;
    $secret = (string) $form_state->getValue('client_secret');
    $connection->saveApp('piknik', $base, trim((string) $form_state->getValue('client_id')), $secret);
    if ($secret !== '') {
      sodium_memzero($secret);
    }
  }

  /**
   * @return array<string, mixed>
   */
  private static function address(Connection $connection): array {
    if (!self::testing()) {
      return [
        '#type' => 'item',
        '#title' => t('Piknik address'),
        '#plain_text' => self::ADDRESS,
        '#weight' => 20,
      ];
    }
    return [
      '#type' => 'url',
      '#title' => t('Piknik address'),
      '#default_value' => $connection->baseUrl('piknik'),
      '#maxlength' => 255,
      '#weight' => 20,
    ];
  }

  private static function validateCallback(FormStateInterface $form_state): void {
    $callback = trim((string) $form_state->getValue('callback'));
    if ($callback === '') {
      $form_state->setValue('callback', SiteCallback::url('piknik'));
      return;
    }
    if (!self::callbackOk($callback)) {
      $form_state->setErrorByName('callback', t('Enter a callback URL on this farm.'));
    }
  }

  private static function callbackOk(string $url): bool {
    $parts = parse_url($url);
    if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
      return FALSE;
    }
    if (!empty($parts['user']) || !empty($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
      return FALSE;
    }
    $scheme = strtolower((string) $parts['scheme']);
    $host = strtolower((string) $parts['host']);
    if ($scheme === 'https') {
      return TRUE;
    }
    return $scheme === 'http' && in_array($host, ['localhost', '127.0.0.1', 'host.docker.internal'], TRUE);
  }

}
