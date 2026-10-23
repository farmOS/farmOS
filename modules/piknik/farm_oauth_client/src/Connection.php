<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use Drupal\Core\State\StateInterface;

/**
 * Stores one provider's address, client credentials, and sealed tokens.
 */
final class Connection {

  public function __construct(
    private StateInterface $state,
    private SecretBox $secrets,
  ) {}

  public function baseUrl(string $provider): string {
    $value = $this->state->get($this->key($provider, 'base_url'), '');
    return is_string($value) ? $value : '';
  }

  public function clientId(string $provider): string {
    $value = $this->state->get($this->key($provider, 'client_id'), '');
    return is_string($value) ? $value : '';
  }

  public function hasSecret(string $provider): bool {
    return is_string($this->state->get($this->key($provider, 'client_secret')));
  }

  public function hasRefresh(string $provider): bool {
    return is_string($this->state->get($this->key($provider, 'refresh')));
  }

  public function expiresAt(string $provider): int {
    return (int) $this->state->get($this->key($provider, 'expires'), 0);
  }

  public function redirectUri(string $provider): string {
    $value = $this->state->get($this->key($provider, 'redirect_uri'), '');
    return is_string($value) ? $value : '';
  }

  public function saveRedirect(string $provider, string $uri): void {
    $key = $this->key($provider, 'redirect_uri');
    if ($uri === '') {
      $this->state->delete($key);
      return;
    }
    $this->state->set($key, $uri);
  }

  public function saveApp(string $provider, string $base, string $client_id, string $secret): void {
    $this->state->set($this->key($provider, 'base_url'), $base);
    $this->state->set($this->key($provider, 'client_id'), $client_id);
    if ($secret !== '') {
      $this->state->set($this->key($provider, 'client_secret'), $this->secrets->seal($secret));
    }
  }

  public function saveTokens(string $provider, string $access, string $refresh, int $expires): void {
    $this->state->set($this->key($provider, 'access'), $this->secrets->seal($access));
    $this->state->set($this->key($provider, 'refresh'), $this->secrets->seal($refresh));
    $this->state->set($this->key($provider, 'expires'), $expires);
  }

  public function openSecret(string $provider): string {
    return $this->open($provider, 'client_secret');
  }

  public function openAccess(string $provider): string {
    return $this->open($provider, 'access');
  }

  public function openRefresh(string $provider): string {
    return $this->open($provider, 'refresh');
  }

  public function clearTokens(string $provider): void {
    $this->state->delete($this->key($provider, 'access'));
    $this->state->delete($this->key($provider, 'refresh'));
    $this->state->delete($this->key($provider, 'expires'));
  }

  private function open(string $provider, string $name): string {
    $stored = $this->state->get($this->key($provider, $name));
    if (!is_string($stored) || $stored === '') {
      throw new \RuntimeException('This farm is not signed in.');
    }
    return $this->secrets->open($stored);
  }

  private function key(string $provider, string $name): string {
    return 'farm_oauth_client.' . $provider . '.' . $name;
  }

}
