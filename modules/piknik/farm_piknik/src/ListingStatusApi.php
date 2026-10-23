<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_oauth_client\PinnedRequest;

/**
 * Activate / deactivate a listing via Piknik (reactivate ↔ cancel).
 */
final class ListingStatusApi {

  public function __construct(
    private OAuthFlow $flow,
    private PinnedRequest $http,
    private Connection $connection,
  ) {}

  public function toggle(string $listing_id): string {
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $listing_id)) {
      throw new \InvalidArgumentException('Invalid listing id.');
    }
    $status = $this->currentStatus($listing_id);
    if ($status === 'active') {
      $this->request('DELETE', '/api/listings/' . rawurlencode($listing_id), NULL, [200, 204]);
      return 'cancelled';
    }
    if (in_array($status, ['cancelled', 'paused', 'expired', 'fulfilled'], TRUE)) {
      $this->request('POST', '/api/listings/' . rawurlencode($listing_id) . '/reactivate', '{}', [200]);
      return 'active';
    }
    throw new \RuntimeException('This listing cannot be toggled from status "' . $status . '".');
  }

  private function currentStatus(string $listing_id): string {
    $decoded = $this->request('GET', '/api/listings/' . rawurlencode($listing_id), NULL, [200]);
    $status = is_array($decoded) && is_string($decoded['status'] ?? NULL) ? $decoded['status'] : '';
    if ($status === '') {
      throw new \RuntimeException('Piknik did not return the listing status.');
    }
    return $status;
  }

  /**
   * @param list<int> $ok
   */
  private function request(string $method, string $path, ?string $body, array $ok): mixed {
    $token = $this->flow->bearer('piknik');
    try {
      $options = [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
        ],
      ];
      if ($body !== NULL) {
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = $body;
      }
      $response = $this->http->send($this->connection->baseUrl('piknik'), $method, $path, $options);
    }
    finally {
      sodium_memzero($token);
    }
    if (!in_array($response->getStatusCode(), $ok, TRUE)) {
      $decoded = json_decode((string) $response->getBody(), TRUE);
      $msg = is_array($decoded) && is_string($decoded['error'] ?? NULL) ? $decoded['error'] : 'Piknik could not update the listing status.';
      throw new \RuntimeException($msg);
    }
    $raw = (string) $response->getBody();
    return $raw !== '' ? json_decode($raw, TRUE) : NULL;
  }

}
