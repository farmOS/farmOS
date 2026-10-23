<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_oauth_client\PinnedRequest;

/**
 * Lists the Piknik farms the signed-in user can post for.
 */
final class PlaceDirectory {

  public function __construct(
    private OAuthFlow $flow,
    private PinnedRequest $http,
    private Connection $connection,
  ) {}

  /**
   * @return array<string, string>
   */
  public function choices(): array {
    $token = $this->flow->bearer('piknik');
    try {
      $response = $this->http->send($this->connection->baseUrl('piknik'), 'GET', '/api/v2/participants?mine=true&role=FARM&limit=20', [
        'headers' => [
          'Authorization' => 'Bearer ' . $token,
          'Accept' => 'application/json',
        ],
      ]);
    }
    finally {
      sodium_memzero($token);
    }
    $decoded = json_decode((string) $response->getBody(), TRUE);
    if ($response->getStatusCode() !== 200 || !is_array($decoded) || !is_array($decoded['participants'] ?? NULL)) {
      throw new \RuntimeException('Piknik did not return this user\'s places.');
    }
    $choices = [];
    foreach ($decoded['participants'] as $place) {
      if (!is_array($place) || ($place['primaryRole'] ?? '') !== 'FARM') {
        continue;
      }
      if (!is_string($place['id'] ?? NULL) || !preg_match('/^[A-Za-z0-9_-]{8,40}$/', $place['id'])) {
        continue;
      }
      $name = is_string($place['name'] ?? NULL) ? trim($place['name']) : $place['id'];
      $choices[$place['id']] = $name !== '' ? $name : $place['id'];
    }
    return $choices;
  }

}
