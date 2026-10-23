<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_oauth_client\PinnedRequest;

/**
 * HTTP calls to Piknik listings endpoints.
 */
final class ListingClient {

  public function __construct(
    private OAuthFlow $flow,
    private PinnedRequest $http,
    private Connection $connection,
  ) {}

  /**
   * @return list<array<string, mixed>>
   */
  public function mine(): array {
    $decoded = $this->get('/api/listings/my');
    return is_array($decoded) ? array_values(array_filter($decoded, 'is_array')) : [];
  }

  /**
   * @return list<array<string, mixed>>
   */
  public function nearby(float $lat, float $lng, string $query, int $limit = 20, int $max_radius_km = BrowseRadius::DEFAULT): array {
    $radius = BrowseRadius::clamp($max_radius_km);
    $path = '/api/listings?lat=' . rawurlencode((string) $lat)
      . '&lng=' . rawurlencode((string) $lng)
      . '&maxRadius=' . $radius
      . '&limit=' . $limit
      . ($query !== '' ? '&q=' . rawurlencode($query) : '');
    $decoded = $this->get($path);
    $list = is_array($decoded) ? ($decoded['listings'] ?? $decoded) : [];
    return is_array($list) ? array_values(array_filter($list, 'is_array')) : [];
  }

  /**
   * @param array<string, mixed> $payload
   */
  public function create(array $payload): string {
    $token = $this->flow->bearer('piknik');
    try {
      $response = $this->http->send($this->connection->baseUrl('piknik'), 'POST', '/api/listings', [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload, JSON_THROW_ON_ERROR),
      ]);
    }
    finally {
      sodium_memzero($token);
    }
    $decoded = json_decode((string) $response->getBody(), TRUE);
    $id = is_array($decoded) ? ($decoded['id'] ?? NULL) : NULL;
    if ($response->getStatusCode() !== 201 || !is_string($id) || !preg_match('/^[A-Za-z0-9_-]{8,40}$/', $id)) {
      $msg = is_array($decoded) && is_string($decoded['error'] ?? NULL) ? $decoded['error'] : 'Piknik did not accept the listing.';
      throw new \RuntimeException($msg);
    }
    return $id;
  }

  /**
   * @return array{lat: float, lng: float}|null
   */
  public function placeCoords(string $place_id): ?array {
    if (!preg_match('/^[A-Za-z0-9_-]{8,40}$/', $place_id)) {
      return NULL;
    }
    $decoded = $this->get('/api/v2/participants/' . rawurlencode($place_id));
    $place = is_array($decoded) ? ($decoded['participant'] ?? $decoded) : NULL;
    if (!is_array($place)) {
      return NULL;
    }
    $lat = $place['latitude'] ?? NULL;
    $lng = $place['longitude'] ?? NULL;
    if (!is_numeric($lat) || !is_numeric($lng)) {
      return NULL;
    }
    return ['lat' => (float) $lat, 'lng' => (float) $lng];
  }

  private function get(string $path): mixed {
    $token = $this->flow->bearer('piknik');
    try {
      $response = $this->http->send($this->connection->baseUrl('piknik'), 'GET', $path, [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
        ],
      ]);
    }
    finally {
      sodium_memzero($token);
    }
    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException('Piknik did not return listing data.');
    }
    return json_decode((string) $response->getBody(), TRUE);
  }

}
