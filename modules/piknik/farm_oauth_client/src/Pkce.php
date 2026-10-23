<?php

declare(strict_types=1);

namespace Drupal\farm_oauth_client;

/**
 * Builds an S256 PKCE pair. The verifier never leaves this farm.
 */
final class Pkce {

  /**
   * @return array{verifier: string, challenge: string}
   */
  public static function create(): array {
    $verifier = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, TRUE)), '+/', '-_'), '=');
    return ['verifier' => $verifier, 'challenge' => $challenge];
  }

}
