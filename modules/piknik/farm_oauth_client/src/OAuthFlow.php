<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Authorization-code client. The person is sent to the service to sign in.
 */
final class OAuthFlow {

  private const SCOPE = 'marketplace:write';

  public function __construct(
    private Connection $connection,
    private PinnedRequest $http,
    private PrivateTempStoreFactory $tempStore,
    private SecretBox $secrets,
  ) {}

  public function callbackUrl(string $provider): string {
    $provider = $this->name($provider);
    $stored = $this->connection->redirectUri($provider);
    return $stored !== '' ? $stored : SiteCallback::url($provider);
  }

  public function connected(string $provider): bool {
    return $this->connection->hasRefresh($this->name($provider));
  }

  public function disconnect(string $provider): void {
    $this->connection->clearTokens($this->name($provider));
  }

  public function authorizeUrl(string $provider): string {
    $provider = $this->name($provider);
    $base = $this->connection->baseUrl($provider);
    $client_id = $this->connection->clientId($provider);
    if ($base === '' || $client_id === '' || !$this->connection->hasSecret($provider)) {
      throw new \RuntimeException('Save the service address, client id, and client secret first.');
    }
    $created = Pkce::create();
    $verifier = $created['verifier'];
    $state = bin2hex(random_bytes(16));
    try {
      $this->tempStore->get('farm_oauth_client')->set($provider, [
        'state' => $state,
        'verifier' => $this->secrets->seal($verifier),
        'expires' => \Drupal::time()->getRequestTime() + 600,
      ]);
    }
    finally {
      sodium_memzero($verifier);
      sodium_memzero($created['verifier']);
    }
    $query = UrlHelper::buildQuery([
      'response_type' => 'code',
      'client_id' => $client_id,
      'redirect_uri' => $this->callbackUrl($provider),
      'scope' => self::SCOPE,
      'state' => $state,
      'code_challenge' => $created['challenge'],
      'code_challenge_method' => 'S256',
    ]);
    return PublicHttps::pin($base)['url'] . '/api/oauth/authorize?' . $query;
  }

  public function finish(string $provider, Request $request): void {
    $provider = $this->name($provider);
    $error = (string) $request->query->get('error', '');
    if ($error !== '') {
      throw new \RuntimeException($this->namedError($error));
    }
    $code = (string) $request->query->get('code', '');
    $state = (string) $request->query->get('state', '');
    if (!preg_match('/^[A-Za-z0-9_-]{20,128}$/', $code) || !preg_match('/^[a-f0-9]{32}$/', $state)) {
      throw new \RuntimeException('The service did not return a usable sign-in code.');
    }
    $pending = $this->tempStore->get('farm_oauth_client')->get($provider);
    $this->tempStore->get('farm_oauth_client')->delete($provider);
    $saved = is_array($pending) ? (string) ($pending['state'] ?? '') : '';
    $sealed = is_array($pending) ? (string) ($pending['verifier'] ?? '') : '';
    $verifier = '';
    try {
      if ($saved === '' || strlen($saved) !== strlen($state) || !hash_equals($saved, $state) || (int) ($pending['expires'] ?? 0) < \Drupal::time()->getRequestTime()) {
        throw new \RuntimeException('This sign-in attempt expired. Start it again.');
      }
      $verifier = $this->secrets->open($sealed);
      $tokens = $this->exchange($provider, [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $this->callbackUrl($provider),
        'client_id' => $this->connection->clientId($provider),
        'code_verifier' => $verifier,
        'state' => $state,
      ]);
      $this->connection->saveTokens($provider, $tokens['access'], $tokens['refresh'], $tokens['expires']);
      sodium_memzero($tokens['access']);
      sodium_memzero($tokens['refresh']);
    }
    finally {
      if ($verifier !== '') {
        sodium_memzero($verifier);
      }
    }
  }

  public function bearer(string $provider): string {
    $provider = $this->name($provider);
    if ($this->connection->expiresAt($provider) > \Drupal::time()->getRequestTime() + 60) {
      return $this->connection->openAccess($provider);
    }
    $refresh = $this->connection->openRefresh($provider);
    try {
      $tokens = $this->exchange($provider, [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh,
        'client_id' => $this->connection->clientId($provider),
      ]);
    }
    finally {
      sodium_memzero($refresh);
    }
    $this->connection->saveTokens($provider, $tokens['access'], $tokens['refresh'], $tokens['expires']);
    sodium_memzero($tokens['refresh']);
    return $tokens['access'];
  }

  /**
   * @return array{access: string, refresh: string, expires: int}
   */
  private function exchange(string $provider, array $form): array {
    $secret = $this->connection->openSecret($provider);
    $form['client_secret'] = $secret;
    try {
      $response = $this->http->send($this->connection->baseUrl($provider), 'POST', '/api/oauth/token', [
        'headers' => ['Accept' => 'application/json'],
        'form_params' => $form,
      ]);
    }
    finally {
      sodium_memzero($secret);
      sodium_memzero($form['client_secret']);
      if (isset($form['code_verifier']) && is_string($form['code_verifier']) && $form['code_verifier'] !== '') {
        sodium_memzero($form['code_verifier']);
      }
      if (isset($form['refresh_token']) && is_string($form['refresh_token']) && $form['refresh_token'] !== '') {
        sodium_memzero($form['refresh_token']);
      }
    }
    $decoded = json_decode((string) $response->getBody(), TRUE);
    if (!is_array($decoded) || !is_string($decoded['access_token'] ?? NULL) || !is_string($decoded['refresh_token'] ?? NULL)) {
      throw new \RuntimeException($this->namedError(is_array($decoded) ? (string) ($decoded['error'] ?? '') : ''));
    }
    $expires_in = (int) ($decoded['expires_in'] ?? 0);
    if ($expires_in < 60 || $expires_in > 86400) {
      $expires_in = 3600;
    }
    return [
      'access' => $decoded['access_token'],
      'refresh' => $decoded['refresh_token'],
      'expires' => \Drupal::time()->getRequestTime() + $expires_in,
    ];
  }

  private function namedError(string $code): string {
    $code = preg_match('/^[a-z_]{1,40}$/', $code) ? $code : '';
    return 'The service refused the sign-in.' . ($code !== '' ? ' (' . $code . ')' : '');
  }

  private function name(string $provider): string {
    if (!preg_match('/^[a-z]{3,32}$/', $provider)) {
      throw new \InvalidArgumentException('Unknown service.');
    }
    return $provider;
  }

}
